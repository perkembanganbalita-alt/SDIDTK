@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8" x-data="redflagFilter()">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Red Flag Perkembangan Anak (0 - 60 Bulan)</h1>
        <p class="mt-1 text-sm text-slate-500">Kenali tanda keterlambatan perkembangan anak sejak dini.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Content -->
        <div class="lg:col-span-2">
            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Umur</label>
                <select x-model="selectedUmur" class="w-full border-slate-200 rounded-xl focus:ring-primary focus:border-primary shadow-sm text-slate-700 p-3 bg-slate-50">
                    <option value="all">Semua Umur</option>
                    @foreach($tahapans as $tahapan)
                        <option value="{{ $tahapan->umur_min }}-{{ $tahapan->umur_max }}">Usia {{ $tahapan->umur_min }} - {{ $tahapan->umur_max }} Bulan</option>
                    @endforeach
                </select>
            </div>

            <!-- Alert -->
            <div class="bg-danger/5 border border-danger/20 rounded-xl p-5 mb-6 flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-danger/10 text-danger flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-danger mb-1">Perhatian!</h3>
                    <p class="text-sm text-danger/80">Tanda-tanda di bawah ini merupakan indikasi potensi keterlambatan perkembangan. Jika ditemukan, segera konsultasikan ke tenaga kesehatan.</p>
                </div>
            </div>

            <!-- Grid Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($tahapans as $tahapan)
                @php
                    $items = array_filter(array_map('trim', explode(';', $tahapan->red_flags)));
                @endphp
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow" x-show="selectedUmur === 'all' || selectedUmur === '{{ $tahapan->umur_min }}-{{ $tahapan->umur_max }}'">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6 bg-danger/10 w-max px-4 py-2 rounded-full">
                            <svg class="w-5 h-5 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h2 class="text-sm font-bold text-danger">Usia {{ $tahapan->umur_min }} - {{ $tahapan->umur_max }} Bulan</h2>
                        </div>
                        
                        <ul class="space-y-4">
                            @foreach($items as $item)
                                @if(!empty($item))
                                <li class="flex items-start gap-3">
                                    <div class="text-danger/40 mt-1 shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-sm text-slate-600 leading-relaxed">{{ $item }}</span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6">
                <!-- Panel Peringatan -->
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                    <div class="bg-danger px-6 py-5 flex items-center gap-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <h3 class="font-bold text-white text-lg">Panel Peringatan</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jika ditemukan tanda di atas:
                        </p>
                        <p class="text-sm text-slate-600 mb-6 leading-relaxed">Segera konsultasikan ke tenaga kesehatan seperti dokter anak, psikolog, atau terapis tumbuh kembang terdekat.</p>
                        
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-danger/10 text-danger flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">1</div>
                                <span class="text-sm text-slate-600">Catat tanda yang ditemukan beserta umur anak saat itu</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-danger/10 text-danger flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">2</div>
                                <span class="text-sm text-slate-600">Kunjungi Posyandu atau Puskesmas terdekat</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-danger/10 text-danger flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">3</div>
                                <span class="text-sm text-slate-600">Minta rujukan ke spesialis tumbuh kembang anak</span>
                            </li>
                        </ul>

                        <button @click="showModal = true" class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-danger hover:bg-danger/90 text-white font-bold text-sm transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            Lihat Data
                        </button>
                    </div>
                </div>

                @if(isset($bayis) && $bayis->count() > 0)
                <div class="mt-8">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Anak yang Perlu Perhatian
                    </h3>
                    
                    <div class="space-y-4">
                        @php $hasWarning = false; @endphp
                        @foreach($bayis as $bayi)
                            @php
                                $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                                $diff = $tglLahir->diff(\Carbon\Carbon::now());
                                $umurBulan = ($diff->y * 12) + $diff->m;
                                if ($diff->d >= 16) { $umurBulan += 1; }

                                $pemeriksaan = \App\Models\Pemeriksaan::where('bayi_id', $bayi->id)->latest()->first();
                            @endphp

                            @if($pemeriksaan && (strpos($pemeriksaan->hasil_kpsp, 'Meragukan') !== false || strpos($pemeriksaan->hasil_kpsp, 'Penyimpangan') !== false || $pemeriksaan->hasil_tdd == 'Curiga'))
                                @php 
                                    $hasWarning = true; 
                                    $isPenyimpangan = strpos($pemeriksaan->hasil_kpsp, 'Penyimpangan') !== false;
                                @endphp
                                <div class="{{ $isPenyimpangan ? 'bg-danger/10 border-danger/20' : 'bg-warning/10 border-warning/20' }} border rounded-2xl p-4 relative overflow-hidden">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $bayi->nama_bayi }}</h4>
                                            <p class="text-xs text-slate-500">{{ $umurBulan }} bulan &bull; {{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('Y-m-d') }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold {{ $isPenyimpangan ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-warning' }}">
                                            Ada Indikasi
                                        </span>
                                    </div>
                                    <p class="text-xs font-bold {{ $isPenyimpangan ? 'text-danger' : 'text-warning' }} mt-2">
                                        {{ $isPenyimpangan ? 'Perlu Konsultasi' : 'Perlu Stimulasi' }}
                                    </p>
                                </div>
                            @endif
                        @endforeach

                        @if(!$hasWarning)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 text-center">
                                <div class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-600">Semua anak dalam kondisi baik.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Data Anak Berisiko -->
    <div x-show="showModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" style="display: none;">
        <div @click.away="showModal = false" class="bg-white rounded-3xl shadow-xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-4 shrink-0">
                <div class="w-10 h-10 rounded-full bg-danger/10 text-danger flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 leading-tight">Data Anak Berisiko</h2>
                    <p class="text-xs text-slate-500">Anak yang memerlukan perhatian khusus</p>
                </div>
            </div>
            
            <div class="p-6 overflow-y-auto space-y-4 bg-slate-50/50">
                @if(isset($bayis) && $hasWarning)
                    @foreach($bayis as $bayi)
                        @php
                            $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                            $diff = $tglLahir->diff(\Carbon\Carbon::now());
                            $umurBulan = ($diff->y * 12) + $diff->m;
                            if ($diff->d >= 16) { $umurBulan += 1; }

                            $pemeriksaan = \App\Models\Pemeriksaan::where('bayi_id', $bayi->id)->latest()->first();
                        @endphp

                        @if($pemeriksaan && (strpos($pemeriksaan->hasil_kpsp, 'Meragukan') !== false || strpos($pemeriksaan->hasil_kpsp, 'Penyimpangan') !== false || $pemeriksaan->hasil_tdd == 'Curiga'))
                            @php 
                                $isPenyimpangan = strpos($pemeriksaan->hasil_kpsp, 'Penyimpangan') !== false;
                            @endphp
                            <div class="{{ $isPenyimpangan ? 'bg-danger/5 border-danger/20' : 'bg-warning/5 border-warning/20' }} border rounded-2xl p-4 shadow-sm">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-slate-800">{{ $bayi->nama_bayi }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold {{ $isPenyimpangan ? 'bg-danger/10 text-danger' : 'bg-warning/10 text-warning' }}">
                                        Red Flag
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mb-1">{{ $umurBulan }} bulan</p>
                                <p class="text-xs font-bold {{ $isPenyimpangan ? 'text-danger' : 'text-warning' }} mb-2">
                                    {{ $isPenyimpangan ? 'Perlu Konsultasi' : 'Perlu Stimulasi' }}
                                </p>
                                <p class="text-[10px] text-slate-400 mb-4">Terakhir diperiksa: {{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('Y-m-d') }}</p>
                                
                                <div class="bg-white rounded-xl p-3 text-xs text-slate-600 leading-relaxed shadow-sm border border-slate-100">
                                    {{ $isPenyimpangan ? 'Segera buat janji dengan dokter spesialis tumbuh kembang anak.' : 'Tingkatkan stimulasi di rumah dan pantau perkembangan setiap bulan.' }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-full bg-success/10 text-success flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-600">Tidak ada anak yang terindikasi Red Flag saat ini.</p>
                    </div>
                @endif
            </div>

            <div class="p-6 border-t border-slate-100 shrink-0">
                <button @click="showModal = false" class="w-full py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('redflagFilter', () => ({
        selectedUmur: 'all',
        showModal: false
    }))
})
</script>
@endsection
