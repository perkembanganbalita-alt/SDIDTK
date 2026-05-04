<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Tahapan;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Pemeriksaan::with('bayi.orangTua')->orderBy('created_at', 'desc');

        if ($user->role === 'Orangtua') {
            $orangTuaId = $user->orangTua ? $user->orangTua->id : null;
            if ($orangTuaId) {
                $query->whereHas('bayi', function($q) use ($orangTuaId) {
                    $q->where('orang_tua_id', $orangTuaId);
                });
            } else {
                $query->where('id', 0); // No data
            }
        }

        // Fitur Pencarian dan Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('bayi', function($q) use ($search) {
                $q->where('nama_bayi', 'like', "%{$search}%")
                  ->orWhereHas('orangTua', function($q2) use ($search) {
                      $q2->where('nama_ortu', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('kategori')) {
            if ($request->kategori === 'kpsp') {
                $query->whereNotNull('hasil_kpsp');
            } elseif ($request->kategori === 'tdd') {
                $query->whereNull('hasil_kpsp')->whereNotNull('hasil_tdd');
            }
        }

        if ($request->filled('hasil')) {
            $hasil = $request->hasil;
            $query->where(function($q) use ($hasil) {
                if (in_array($hasil, ['Sesuai (S)', 'Meragukan (M)', 'Penyimpangan (P)'])) {
                    $q->where('hasil_kpsp', $hasil);
                } elseif (in_array($hasil, ['Normal', 'Curiga'])) {
                    $q->where('hasil_tdd', $hasil);
                }
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tgl_pemeriksaan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tgl_pemeriksaan', $request->tahun);
        }

        $totalPemeriksaan = (clone $query)->count();
        $totalSesuai = (clone $query)->where('hasil_kpsp', 'like', '%Sesuai%')->count();
        $totalMeragukan = (clone $query)->where('hasil_kpsp', 'like', '%Meragukan%')->count();
        $totalRujukan = (clone $query)->where(function($q) {
            $q->where('hasil_kpsp', 'like', '%Penyimpangan%')
              ->orWhere('hasil_tdd', 'Curiga');
        })->count();

        $riwayat = $query->paginate(15)->withQueryString();
        return view('riwayat.index', compact('riwayat', 'totalPemeriksaan', 'totalSesuai', 'totalMeragukan', 'totalRujukan'));
    }

    public function downloadPdf(Pemeriksaan $pemeriksaan)
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
        if ($pemeriksaan->hasil_kpsp == 'Sesuai (S)') {
            // Sesuai: lanjut ke tahapan berikutnya (walaupun TDD Curiga, stimulasi tetap lanjut)
            $tahapanToShow = $tahapanBerikutnya;
        } elseif ($pemeriksaan->hasil_kpsp == 'Meragukan (M)') {
            // Meragukan: sesuai umur
            $tahapanToShow = $tahapanSaatIni;
        } else {
            // Jika Penyimpangan (P), jangan tampilkan Tahapan & Stimulasi
            $tahapanToShow = null;
        }

        $jenis = $pemeriksaan->hasil_kpsp ? 'kpsp' : 'tdd';
        $pdf = Pdf::loadView('riwayat.pdf', compact('pemeriksaan', 'tahapanToShow', 'jenis'));
        return $pdf->download("Hasil_Skrining_{$pemeriksaan->bayi->nama_bayi}.pdf");
    }

    public function exportAll(Request $request)
    {
        $user = auth()->user();
        $query = Pemeriksaan::with('bayi.orangTua')->orderBy('tgl_pemeriksaan', 'desc');

        if ($user->role === 'Orangtua') {
            abort(403);
        }

        $riwayat = $query->get();

        $filename = 'riwayat_pemeriksaan_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($riwayat) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, ['No', 'Tanggal', 'Nama Anak', 'Nama Orang Tua', 'Umur (Bulan)', 'Jenis Kelamin', 'Kategori', 'Skor', 'Hasil']);

            foreach ($riwayat as $index => $item) {
                $jenis = $item->hasil_kpsp ? 'KPSP' : 'TDD';
                $skor = $jenis === 'KPSP' ? ($item->skor_kpsp ?? '-') . '/10' : ($item->skor_tdd ?? '-');
                $hasil = $jenis === 'KPSP' ? $item->hasil_kpsp : $item->hasil_tdd;
                $jk = $item->bayi->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';

                fputcsv($file, [
                    $index + 1,
                    \Carbon\Carbon::parse($item->tgl_pemeriksaan)->format('d/m/Y'),
                    $item->bayi->nama_bayi ?? '-',
                    $item->bayi->orangTua->nama_ortu ?? '-',
                    $item->umur_saat_periksa_bulan,
                    $jk,
                    $jenis,
                    $skor,
                    $hasil ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
