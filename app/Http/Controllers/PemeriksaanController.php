<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrangTua;
use App\Models\Bayi;
use App\Models\Pemeriksaan;
use App\Models\MasterKpsp;
use App\Models\MasterTdd;
use App\Models\KpspJawaban;
use App\Models\TddJawaban;
use App\Models\Tahapan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\HasilSkriningMail;

class PemeriksaanController extends Controller
{
    public function index($jenis)
    {
        if (Auth::user()->role === 'Orangtua') abort(403);

        $bayis = Bayi::with('orangTua')->get();
        return view('pemeriksaan.index', compact('bayis', 'jenis'));
    }

    public function storeBayi(Request $request, $jenis)
    {
        if (Auth::user()->role === 'Orangtua') abort(403);

        $request->validate([
            'bayi_id' => 'required|exists:bayis,id',
            'tgl_pemeriksaan' => 'required|date'
        ]);

        $bayi = Bayi::findOrFail($request->bayi_id);

        // Hitung umur
        $tglLahir = Carbon::parse($bayi->tgl_lahir);
        $tglPeriksa = Carbon::parse($request->tgl_pemeriksaan);
        
        $diff = $tglLahir->diff($tglPeriksa);
        $umurBulan = ($diff->y * 12) + $diff->m;
        
        if ($diff->d >= 16) {
            $umurBulan += 1;
        }

        // Cari jadwal standar KPSP yang lebih muda jika tidak ada
        $availableAges = MasterKpsp::select('umur_bulan')->distinct()->orderBy('umur_bulan', 'desc')->pluck('umur_bulan')->toArray();
        
        $umurSkrining = 0;
        foreach ($availableAges as $age) {
            if ($umurBulan >= $age) {
                $umurSkrining = $age;
                break;
            }
        }

        // Jika umur bayi kurang dari jadwal terendah KPSP (misal < 3 bulan), gunakan yang paling awal
        if ($umurSkrining == 0 && count($availableAges) > 0) {
            $umurSkrining = min($availableAges);
        }

        $pemeriksaan = Pemeriksaan::create([
            'bayi_id' => $bayi->id,
            'nakes_id' => Auth::id(),
            'tgl_pemeriksaan' => $request->tgl_pemeriksaan,
            'umur_saat_periksa_bulan' => $umurSkrining,
        ]);

        return redirect()->route('pemeriksaan.kuesioner', ['jenis' => $jenis, 'pemeriksaan' => $pemeriksaan->id]);
    }

    public function kuesioner($jenis, Pemeriksaan $pemeriksaan)
    {
        if (Auth::user()->role === 'Orangtua') abort(403);

        $umur = $pemeriksaan->umur_saat_periksa_bulan;
        $kpsp = collect();
        $tdd = collect();

        if ($jenis === 'kpsp') {
            $kpsp = MasterKpsp::where('umur_bulan', $umur)->get();
        } elseif ($jenis === 'tdd') {
            // Ambil TDD yang tepat (hindari overlap range umur)
            $tdd = MasterTdd::where('umur_max', '!=', 60) // Abaikan data anomali 0-60
                    ->where(function($query) use ($umur) {
                        if ($umur == 0) {
                            $query->where('umur_min', '<=', $umur)->where('umur_max', '>=', $umur);
                        } else {
                            $query->where('umur_min', '<', $umur)->where('umur_max', '>=', $umur);
                        }
                    })->get();
                    
            // Jika kosong (misal umur di batas atas > 36), pakai rentang yang paling mencakupnya
            if($tdd->isEmpty()) {
                $tdd = MasterTdd::where('umur_min', '<=', $umur)
                        ->where('umur_max', '>=', $umur)
                        ->get();
            }
        }

        return view('pemeriksaan.kuesioner', compact('pemeriksaan', 'kpsp', 'tdd', 'jenis'));
    }

    public function submitKuesioner(Request $request, $jenis, Pemeriksaan $pemeriksaan)
    {
        if (Auth::user()->role === 'Orangtua') abort(403);

        $kpspAns = $request->input('kpsp', []);
        $tddAns = $request->input('tdd', []);

        if ($jenis === 'kpsp') {
            $skorKpsp = 0;
            foreach ($kpspAns as $id => $jawaban) {
                $mk = MasterKpsp::find($id);
                if ($mk) {
                    KpspJawaban::create([
                        'pemeriksaan_id' => $pemeriksaan->id,
                        'pertanyaan' => $mk->pertanyaan,
                        'sub_kategori' => $mk->sub_kategori,
                        'jawaban' => $jawaban
                    ]);
                    if ($jawaban == 'Ya') {
                        $skorKpsp++;
                    }
                }
            }

            $hasilKpsp = 'Penyimpangan (P)';
            if ($skorKpsp >= 9) {
                $hasilKpsp = 'Sesuai (S)';
            } elseif ($skorKpsp >= 7) {
                $hasilKpsp = 'Meragukan (M)';
            }

            $pemeriksaan->update([
                'skor_kpsp' => $skorKpsp,
                'hasil_kpsp' => $hasilKpsp,
            ]);
        } elseif ($jenis === 'tdd') {
            $skorTdd = 0;
            foreach ($tddAns as $id => $jawaban) {
                $mt = MasterTdd::find($id);
                if ($mt) {
                    TddJawaban::create([
                        'pemeriksaan_id' => $pemeriksaan->id,
                        'pertanyaan' => $mt->pertanyaan,
                        'jawaban' => $jawaban
                    ]);
                    if ($jawaban == 'Tidak') {
                        $skorTdd++; // TDD skor is number of "Tidak"
                    }
                }
            }

            $hasilTdd = $skorTdd > 0 ? 'Curiga' : 'Normal';

            $pemeriksaan->update([
                'skor_tdd' => $skorTdd,
                'hasil_tdd' => $hasilTdd,
            ]);
        }

        return redirect()->route('pemeriksaan.hasil', ['jenis' => $jenis, 'pemeriksaan' => $pemeriksaan->id])
            ->with('success', 'Pemeriksaan selesai!')
            ->with('send_email', true);
    }

    public function hasil($jenis, Pemeriksaan $pemeriksaan)
    {
        if (Auth::user()->role === 'Orangtua' && $pemeriksaan->bayi?->orang_tua_id !== Auth::user()->orangTua?->id) {
            abort(403);
        }

        $pemeriksaan->load('bayi.orangTua', 'kpsp_jawabans', 'tdd_jawabans', 'nakes');
        $umurSkrining = $pemeriksaan->umur_saat_periksa_bulan;
        
        // Sesuai: Lanjut tahapan usia berikutnya
        // Cari tahapan yang batas bawahnya > umur skrining (tahapan berikutnya)
        $tahapanBerikutnya = Tahapan::where('umur_min', '>', $umurSkrining)->orderBy('umur_min', 'asc')->first();
        if (!$tahapanBerikutnya) {
            // Jika tidak ada tahapan berikutnya, gunakan tahapan saat ini
            $tahapanBerikutnya = Tahapan::where('umur_min', '<=', $umurSkrining)->where('umur_max', '>=', $umurSkrining)->first();
        }

        // Meragukan: Tahapan sesuai umur skrining
        $tahapanSaatIni = Tahapan::where('umur_min', '<=', $umurSkrining)->where('umur_max', '>=', $umurSkrining)->first();
        if (!$tahapanSaatIni) {
             // Fallback jika tidak pas
             $tahapanSaatIni = Tahapan::where('umur_max', '>=', $umurSkrining)->orderBy('umur_max', 'asc')->first();
        }

        $tahapanToShow = null;
        if ($jenis === 'kpsp') {
            if ($pemeriksaan->hasil_kpsp == 'Sesuai (S)') {
                $tahapanToShow = $tahapanBerikutnya;
            } elseif ($pemeriksaan->hasil_kpsp == 'Meragukan (M)') {
                $tahapanToShow = $tahapanSaatIni;
            } else {
                $tahapanToShow = null;
            }
        } elseif ($jenis === 'tdd') {
            if ($pemeriksaan->hasil_tdd == 'Normal') {
                $tahapanToShow = $tahapanBerikutnya;
            } elseif ($pemeriksaan->hasil_tdd == 'Curiga') {
                $tahapanToShow = $tahapanSaatIni;
            } else {
                $tahapanToShow = null;
            }
        }

        // Kirim email hasil skrining ke orang tua
        if (session('send_email') && $pemeriksaan->bayi->orangTua->email_ortu) {
            try {
                $pdf = Pdf::loadView('riwayat.pdf', compact('pemeriksaan', 'tahapanToShow', 'jenis'));
                
                Mail::to($pemeriksaan->bayi->orangTua->email_ortu)
                    ->send(new HasilSkriningMail($pemeriksaan, $jenis, $pdf->output()));
            } catch (\Exception $e) {
                // Ignore mail error if not configured
            }
        }

        return view('pemeriksaan.hasil', compact('pemeriksaan', 'tahapanToShow', 'jenis'));
    }
}
