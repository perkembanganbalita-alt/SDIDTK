<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Pemeriksaan::query()->with('bayi.orangTua');
        $bayiQuery = \App\Models\Bayi::query();

        if ($user->role === 'Orangtua') {
            $orangTuaId = $user->orangTua ? $user->orangTua->id : null;
            if ($orangTuaId) {
                $query->whereHas('bayi', function($q) use ($orangTuaId) {
                    $q->where('orang_tua_id', $orangTuaId);
                });
                $bayiQuery->where('orang_tua_id', $orangTuaId);
            } else {
                $query->where('id', 0); // No data
                $bayiQuery->where('id', 0);
            }
        }

        $totalPemeriksaan = (clone $query)->count();
        $totalSesuai = (clone $query)->where('hasil_kpsp', 'Sesuai (S)')->count();
        $totalMeragukan = (clone $query)->where('hasil_kpsp', 'Meragukan (M)')->count();
        $totalPenyimpangan = (clone $query)->where('hasil_kpsp', 'Penyimpangan (P)')->count();
        $totalTddCuriga = (clone $query)->where('hasil_tdd', 'Curiga')->count();

        $totalTddNormal = (clone $query)->where('hasil_tdd', 'Normal')->count();

        // New Stats
        $totalBayi = (clone $bayiQuery)->count();
        $bayiBaruBulanIni = (clone $bayiQuery)->whereMonth('created_at', now()->month)
                                            ->whereYear('created_at', now()->year)->count();
        
        $pemeriksaanHariIni = (clone $query)->whereDate('tgl_pemeriksaan', now()->toDateString())->count();
        // Assuming 'belum selesai' means created but missing results, but here we just pass 0 for mockup if we don't track status
        $pemeriksaanBelumSelesai = 0;

        // Data for Chart
        $chartData = [
            'Sesuai (S)' => $totalSesuai,
            'Meragukan (M)' => $totalMeragukan,
            'Penyimpangan (P)' => $totalPenyimpangan,
        ];

        // Monthly Data (Past 6 months)
        $monthlyChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyChart['labels'][] = $date->translatedFormat('M y');
            
            $monthSesuai = (clone $query)->whereMonth('tgl_pemeriksaan', $date->month)->whereYear('tgl_pemeriksaan', $date->year)->where('hasil_kpsp', 'Sesuai (S)')->count();
            $monthMeragukan = (clone $query)->whereMonth('tgl_pemeriksaan', $date->month)->whereYear('tgl_pemeriksaan', $date->year)->where('hasil_kpsp', 'Meragukan (M)')->count();
            $monthPenyimpangan = (clone $query)->whereMonth('tgl_pemeriksaan', $date->month)->whereYear('tgl_pemeriksaan', $date->year)->where('hasil_kpsp', 'Penyimpangan (P)')->count();
            
            $monthTddNormal = (clone $query)->whereMonth('tgl_pemeriksaan', $date->month)->whereYear('tgl_pemeriksaan', $date->year)->where('hasil_tdd', 'Normal')->count();
            $monthTddCuriga = (clone $query)->whereMonth('tgl_pemeriksaan', $date->month)->whereYear('tgl_pemeriksaan', $date->year)->where('hasil_tdd', 'Curiga')->count();
            
            $monthlyChart['sesuai'][] = $monthSesuai;
            $monthlyChart['meragukan'][] = $monthMeragukan;
            $monthlyChart['penyimpangan'][] = $monthPenyimpangan;
            $monthlyChart['tdd_normal'][] = $monthTddNormal;
            $monthlyChart['tdd_curiga'][] = $monthTddCuriga;
        }

        // Aktivitas Terbaru (last 5)
        $aktivitasTerbaru = (clone $query)->orderBy('tgl_pemeriksaan', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalPemeriksaan',
            'totalSesuai',
            'totalMeragukan',
            'totalPenyimpangan',
            'totalTddNormal',
            'totalTddCuriga',
            'chartData',
            'totalBayi',
            'bayiBaruBulanIni',
            'pemeriksaanHariIni',
            'pemeriksaanBelumSelesai',
            'monthlyChart',
            'aktivitasTerbaru'
        ));
    }
}
