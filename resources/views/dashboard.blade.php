@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'Orangtua')
    <div class="max-w-7xl mx-auto" x-data="{ chartType: 'pie' }">
        <!-- Hero Banner -->
        <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="text-sm text-slate-500">Pantau perkembangan anak Anda secara berkala.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">Jumlah Anak</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalBayi }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">Total Pemeriksaan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPemeriksaan }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">Hasil Sesuai</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalSesuai }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">Hasil Meragukan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalMeragukan }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-warning/10 text-warning flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 mb-1">Penyimpangan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPenyimpangan }}</h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-danger/10 text-danger flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Charts Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-base font-bold text-slate-800">Distribusi Hasil KPSP</h3>
                    <div class="flex bg-slate-100 p-1 rounded-lg">
                        <button @click="chartType = 'pie'" :class="chartType === 'pie' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1 text-xs font-medium rounded-md transition-all">Pie</button>
                        <button @click="chartType = 'bar'" :class="chartType === 'bar' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="px-3 py-1 text-xs font-medium rounded-md transition-all">Bar</button>
                    </div>
                </div>
                
                <div class="relative h-64 flex justify-center" x-show="chartType === 'pie'">
                    <canvas id="kpspPieChart"></canvas>
                </div>
                <div class="relative h-64" x-show="chartType === 'bar'" style="display: none;">
                    <canvas id="kpspBarChart"></canvas>
                </div>
                
                <div class="flex justify-center items-center gap-6 mt-6 flex-wrap">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-success"></span><span class="text-xs text-slate-600 font-medium">Sesuai Umur</span></div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-warning"></span><span class="text-xs text-slate-600 font-medium">Meragukan</span></div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-danger"></span><span class="text-xs text-slate-600 font-medium">Penyimpangan</span></div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span><span class="text-xs text-slate-600 font-medium">TDD Normal</span></div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-purple-500"></span><span class="text-xs text-slate-600 font-medium">TDD Curiga</span></div>
                </div>
            </div>

            <!-- Peringatan TDD -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-6">Peringatan TDD</h3>
                @if($totalTddCuriga > 0)
                <div class="bg-amber-50 rounded-xl p-5 border border-amber-100/50">
                    <div class="flex gap-4">
                        <div class="text-amber-500 shrink-0 mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700 mb-1">Ada pemeriksaan TDD yang perlu diperhatikan.</p>
                            @php
                                $currentUser = Auth::user();
                                $curigaTdd = \App\Models\Pemeriksaan::whereHas('bayi', function($q) use ($currentUser) {
                                    $q->where('orang_tua_id', $currentUser->orangTua->id ?? 0);
                                })->where('hasil_tdd', 'Curiga')->latest()->first();
                            @endphp
                            @if($curigaTdd)
                            <p class="text-xs text-slate-500 mb-4">{{ $curigaTdd->bayi->nama_bayi }} - {{ \Carbon\Carbon::parse($curigaTdd->tgl_pemeriksaan)->format('Y-m-d') }}</p>
                            <a href="{{ route('bayi.index') }}" class="inline-block w-full py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg text-center transition-colors">Lihat Data</a>
                            @endif
                        </div>
                    </div>
                </div>
                @else
                <div class="flex flex-col items-center justify-center text-center py-10 h-full border-2 border-dashed border-slate-100 rounded-xl">
                    <div class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="text-sm text-slate-500">Tidak ada peringatan TDD.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Pemeriksaan Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Riwayat Pemeriksaan Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-6 font-semibold">Tanggal</th>
                            <th class="py-3 px-6 font-semibold">Nama Anak</th>
                            <th class="py-3 px-6 font-semibold">Kategori</th>
                            <th class="py-3 px-6 font-semibold">Hasil</th>
                            <th class="py-3 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($aktivitasTerbaru as $aktif)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-600 font-medium">{{ \Carbon\Carbon::parse($aktif->tgl_pemeriksaan)->format('Y-m-d') }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-slate-800">{{ $aktif->bayi->nama_bayi ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @php $jenisAktif = $aktif->hasil_kpsp ? 'kpsp' : 'tdd'; @endphp
                                <span class="font-bold text-slate-700 uppercase">{{ $jenisAktif }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($jenisAktif === 'kpsp')
                                    @if(strpos($aktif->hasil_kpsp, 'Sesuai') !== false)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-success/10 text-success">Sesuai Umur</span>
                                    @elseif(strpos($aktif->hasil_kpsp, 'Meragukan') !== false)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-warning/10 text-warning">Meragukan</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-danger/10 text-danger">Penyimpangan</span>
                                    @endif
                                @else
                                    @if($aktif->hasil_tdd === 'Normal')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-success/10 text-success">Normal</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-danger/10 text-danger">{{ $aktif->hasil_tdd }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('pemeriksaan.hasil', ['jenis' => $jenisAktif, 'pemeriksaan' => $aktif->id]) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors text-xs font-bold">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </a>
                                    <a href="{{ route('riwayat.pdf', $aktif->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 transition-colors text-xs font-bold" target="_blank">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Ekspor PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($aktivitasTerbaru->isEmpty())
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500 text-sm">Belum ada aktivitas terbaru</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartData = [{{ $totalSesuai }}, {{ $totalMeragukan }}, {{ $totalPenyimpangan }}, {{ $totalTddNormal }}, {{ $totalTddCuriga }}];
        
        // Pie Chart
        const pieCtx = document.getElementById('kpspPieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sesuai Umur', 'Meragukan', 'Penyimpangan', 'TDD Normal', 'TDD Curiga'],
                datasets: [{ data: chartData, backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#a855f7'], borderWidth: 0, hoverOffset: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { display: false } } }
        });

        // Bar Chart
        const barCtx = document.getElementById('kpspBarChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Sesuai Umur', 'Meragukan', 'Penyimpangan', 'TDD Normal', 'TDD Curiga'],
                datasets: [{
                    data: chartData,
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#a855f7'],
                    borderRadius: 4,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { border: { display: false }, beginAtZero: true }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
    </script>

@else
    <!-- Admin/Nakes Dashboard -->
    <div class="max-w-7xl mx-auto">
        <!-- Hero Banner -->
        <div class="bg-primary rounded-2xl p-8 text-white mb-8 shadow-sm flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-64 h-64 bg-black/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 text-center md:text-left mb-6 md:mb-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h1>
                <p class="text-primary-50 text-sm md:text-base opacity-90">{{ now()->translatedFormat('l, d F Y') }}</p>
                <p class="text-white mt-1 font-medium">Ada {{ $pemeriksaanHariIni }} pemeriksaan yang dijadwalkan hari ini.</p>
            </div>
            <div class="relative z-10">
                <a href="{{ route('pemeriksaan.index', ['jenis' => 'kpsp']) }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-primary rounded-xl font-bold shadow-sm hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Mulai Pemeriksaan
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalBayi }}</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Total Anak</p>
                    <p class="text-[10px] text-slate-400 mt-1">+{{ $bayiBaruBulanIni }} bulan ini</p>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $pemeriksaanHariIni }}</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Pemeriksaan Hari Ini</p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $pemeriksaanBelumSelesai }} belum selesai</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalSesuai }}</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Sesuai Umur</p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $totalPemeriksaan > 0 ? round(($totalSesuai/$totalPemeriksaan)*100, 1) : 0 }}% dari total</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-10 h-10 rounded-full bg-warning/10 text-warning flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalMeragukan }}</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Meragukan</p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $totalPemeriksaan > 0 ? round(($totalMeragukan/$totalPemeriksaan)*100, 1) : 0 }}% dari total</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="w-10 h-10 rounded-full bg-danger/10 text-danger flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPenyimpangan }}</h3>
                    <p class="text-xs font-semibold text-slate-500 mt-1">Penyimpangan</p>
                    <p class="text-[10px] text-slate-400 mt-1">{{ $totalPemeriksaan > 0 ? round(($totalPenyimpangan/$totalPemeriksaan)*100, 1) : 0 }}% dari total</p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Grafik Pemeriksaan Bulanan</h3>
                        <p class="text-xs text-slate-500">6 bulan terakhir</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium flex-wrap">
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-success"></span> Sesuai</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-warning"></span> Meragukan</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-danger"></span> Rujukan</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> TDD Normal</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> TDD Curiga</div>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800">Grafik Status Anak</h3>
                <p class="text-xs text-slate-500 mb-6">Distribusi hasil pemeriksaan</p>
                <div class="relative h-48 flex justify-center mb-6">
                    <canvas id="statusChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-slate-800">{{ $totalPemeriksaan }}</span>
                        <span class="text-[10px] text-slate-500">Total</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-success"></span><span class="text-slate-600">Sesuai Umur</span></div>
                        <div class="font-medium text-slate-800">{{ $totalSesuai }} <span class="text-slate-400 text-xs ml-1">({{ $totalPemeriksaan > 0 ? round(($totalSesuai/$totalPemeriksaan)*100) : 0 }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-warning"></span><span class="text-slate-600">Meragukan</span></div>
                        <div class="font-medium text-slate-800">{{ $totalMeragukan }} <span class="text-slate-400 text-xs ml-1">({{ $totalPemeriksaan > 0 ? round(($totalMeragukan/$totalPemeriksaan)*100) : 0 }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-danger"></span><span class="text-slate-600">Penyimpangan</span></div>
                        <div class="font-medium text-slate-800">{{ $totalPenyimpangan }} <span class="text-slate-400 text-xs ml-1">({{ $totalPemeriksaan > 0 ? round(($totalPenyimpangan/$totalPemeriksaan)*100) : 0 }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span><span class="text-slate-600">TDD Normal</span></div>
                        <div class="font-medium text-slate-800">{{ $totalTddNormal }} <span class="text-slate-400 text-xs ml-1">({{ $totalPemeriksaan > 0 ? round(($totalTddNormal/$totalPemeriksaan)*100) : 0 }}%)</span></div>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span><span class="text-slate-600">TDD Curiga</span></div>
                        <div class="font-medium text-slate-800">{{ $totalTddCuriga }} <span class="text-slate-400 text-xs ml-1">({{ $totalPemeriksaan > 0 ? round(($totalTddCuriga/$totalPemeriksaan)*100) : 0 }}%)</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
            <a href="{{ route('pemeriksaan.index', ['jenis' => 'kpsp']) }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-3 hover:bg-slate-50 transition-colors group">
                <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-sm font-semibold text-slate-700">Pemeriksaan Baru</span>
            </a>
            <a href="{{ route('riwayat.index') }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col items-center justify-center gap-3 hover:bg-slate-50 transition-colors group">
                <div class="w-12 h-12 rounded-full bg-warning/10 text-warning flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-semibold text-slate-700">Lihat Riwayat</span>
            </a>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Aktivitas Terbaru</h3>
                    <p class="text-xs text-slate-500">Pemeriksaan terbaru</p>
                </div>
                <a href="{{ route('riwayat.index') }}" class="text-sm font-medium text-primary hover:text-primary/80">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500">
                            <th class="py-3 px-6 font-semibold">Nama Anak</th>
                            <th class="py-3 px-6 font-semibold">Umur</th>
                            <th class="py-3 px-6 font-semibold">Kategori</th>
                            <th class="py-3 px-6 font-semibold">Hasil</th>
                            <th class="py-3 px-6 font-semibold">Tanggal</th>
                            <th class="py-3 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach($aktivitasTerbaru as $aktif)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($aktif->bayi->nama_bayi ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="font-medium text-slate-800">{{ $aktif->bayi->nama_bayi ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-slate-600">{{ $aktif->umur_saat_periksa_bulan }} Bulan</td>
                            <td class="py-3 px-6">
                                @php $jenisAktif = $aktif->hasil_kpsp ? 'kpsp' : 'tdd'; @endphp
                                <span class="font-bold text-slate-700 uppercase">{{ $jenisAktif }}</span>
                            </td>
                            <td class="py-3 px-6">
                                @if($jenisAktif === 'kpsp')
                                    @if(strpos($aktif->hasil_kpsp, 'Sesuai') !== false)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-success/10 text-success">Sesuai Umur</span>
                                    @elseif(strpos($aktif->hasil_kpsp, 'Meragukan') !== false)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-warning/10 text-warning">Meragukan</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-danger/10 text-danger">Penyimpangan</span>
                                    @endif
                                @else
                                    @if($aktif->hasil_tdd === 'Normal')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-success/10 text-success">Normal</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-danger/10 text-danger">{{ $aktif->hasil_tdd }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-3 px-6 text-slate-600">{{ \Carbon\Carbon::parse($aktif->tgl_pemeriksaan)->translatedFormat('d M Y') }}</td>
                            <td class="py-3 px-6">
                                <a href="{{ route('pemeriksaan.hasil', ['jenis' => $jenisAktif, 'pemeriksaan' => $aktif->id]) }}" class="text-primary hover:text-primary/80 font-medium text-xs">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                        @if($aktivitasTerbaru->isEmpty())
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-500 text-sm">Belum ada aktivitas terbaru</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Sesuai Umur', 'Meragukan', 'Penyimpangan', 'TDD Normal', 'TDD Curiga'],
                datasets: [{
                    data: [{{ $totalSesuai }}, {{ $totalMeragukan }}, {{ $totalPenyimpangan }}, {{ $totalTddNormal }}, {{ $totalTddCuriga }}],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#3b82f6', '#a855f7'],
                    borderWidth: 0, hoverOffset: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: { legend: { display: false }, tooltip: { padding: 10 } }
            }
        });

        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: @json($monthlyChart['labels'] ?? []),
                datasets: [
                    {
                        label: 'Sesuai',
                        data: @json($monthlyChart['sesuai'] ?? []),
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                        barThickness: 30
                    },
                    {
                        label: 'Meragukan',
                        data: @json($monthlyChart['meragukan'] ?? []),
                        backgroundColor: '#f59e0b',
                        borderRadius: 4,
                        barThickness: 30
                    },
                    {
                        label: 'Rujukan',
                        data: @json($monthlyChart['penyimpangan'] ?? []),
                        backgroundColor: '#ef4444',
                        borderRadius: 4,
                        barThickness: 30
                    },
                    {
                        label: 'TDD Normal',
                        data: @json($monthlyChart['tdd_normal'] ?? []),
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                        barThickness: 30
                    },
                    {
                        label: 'TDD Curiga',
                        data: @json($monthlyChart['tdd_curiga'] ?? []),
                        backgroundColor: '#a855f7',
                        borderRadius: 4,
                        barThickness: 30
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                scales: {
                    x: { stacked: true, grid: { display: false } },
                    y: { stacked: true, border: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });
    });
    </script>
@endif
@endsection
