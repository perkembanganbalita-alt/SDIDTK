<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bayi;
use Illuminate\Support\Facades\Auth;

class BayiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'Orangtua') {
            $orangTua = $user->orangTua;
            $bayis = $orangTua ? $orangTua->bayis : collect();
            return view('orangtua.bayi.index', compact('bayis'));
        } else {
            // Admin or Nakes
            $bayis = Bayi::with('orangTua.user')->get(); // include User for alamat? Wait, OrangTua model has alamat?
            $totalAnak = $bayis->count();
            $lakiLakiCount = $bayis->where('jenis_kelamin', 'L')->count();
            $perempuanCount = $bayis->where('jenis_kelamin', 'P')->count();
            return view('orangtua.bayi.index', compact('bayis', 'totalAnak', 'lakiLakiCount', 'perempuanCount'));
        }
    }

    public function create()
    {
        if (Auth::user()->role !== 'Orangtua') abort(403);
        return view('orangtua.bayi.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'Orangtua') abort(403);

        $request->validate([
            'nama_bayi' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $orangTua = Auth::user()->orangTua;

        Bayi::create([
            'orang_tua_id' => $orangTua?->id,
            'nama_bayi' => $request->nama_bayi,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('bayi.index')->with('success', 'Data bayi berhasil ditambahkan.');
    }

    public function edit(Bayi $bayi)
    {
        $this->authorizeBayi($bayi);
        return view('orangtua.bayi.edit', compact('bayi'));
    }

    public function update(Request $request, Bayi $bayi)
    {
        $this->authorizeBayi($bayi);

        $request->validate([
            'nama_bayi' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $bayi->update([
            'nama_bayi' => $request->nama_bayi,
            'tgl_lahir' => $request->tgl_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('bayi.index')->with('success', 'Data bayi berhasil diperbarui.');
    }

    public function destroy(Bayi $bayi)
    {
        $this->authorizeBayi($bayi);
        $bayi->delete();
        return redirect()->route('bayi.index')->with('success', 'Data bayi berhasil dihapus.');
    }

    public function authorizeBayi(Bayi $bayi)
    {
        if (Auth::user()->role !== 'Orangtua') {
            abort(403, 'Unauthorized action.');
        }
        if ($bayi->orang_tua_id !== Auth::user()->orangTua->id) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function export()
    {
        if (Auth::user()->role === 'Orangtua') abort(403);

        $bayis = Bayi::with('orangTua')->get();
        $fileName = 'data_anak_posyandu.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('No', 'Nama Anak', 'Nama Orang Tua', 'Jenis Kelamin', 'Tanggal Lahir', 'Umur (Bulan)');

        $callback = function() use($bayis, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bayis as $index => $bayi) {
                $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                $diff = $tglLahir->diff(\Carbon\Carbon::now());
                $umurBulan = ($diff->y * 12) + $diff->m;
                if ($diff->d >= 16) $umurBulan += 1;

                $row['No']  = $index + 1;
                $row['Nama Anak']    = $bayi->nama_bayi;
                $row['Nama Orang Tua']  = $bayi->orangTua->nama_ortu ?? '-';
                $row['Jenis Kelamin']  = $bayi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
                $row['Tanggal Lahir']  = $bayi->tgl_lahir;
                $row['Umur (Bulan)']  = $umurBulan;

                fputcsv($file, array($row['No'], $row['Nama Anak'], $row['Nama Orang Tua'], $row['Jenis Kelamin'], $row['Tanggal Lahir'], $row['Umur (Bulan)']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
