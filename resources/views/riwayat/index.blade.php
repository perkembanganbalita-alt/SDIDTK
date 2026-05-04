@extends('layouts.app')

@section('content')
@if(Auth::user()->role === 'Orangtua')
<!-- Orangtua View (Original) -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">Riwayat Pemeriksaan</h1>
            <p class="mt-2 text-sm text-slate-500">Daftar semua hasil skrining balita Anda.</p>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
        <div class="p-6">
            <form x-data="{
                kategori: '{{ request('kategori') }}',
                hasilDefault: '{{ request('hasil') }}',
                get hasilOptions() {
                    let opts = [];
                    if (this.kategori === '' || this.kategori === 'kpsp') {
                        opts.push({ value: 'Sesuai (S)', label: 'Sesuai (S)' });
                        opts.push({ value: 'Meragukan (M)', label: 'Meragukan (M)' });
                        opts.push({ value: 'Penyimpangan (P)', label: 'Penyimpangan (P)' });
                    }
                    if (this.kategori === '' || this.kategori === 'tdd') {
                        opts.push({ value: 'Normal', label: 'Normal' });
                        opts.push({ value: 'Curiga', label: 'Curiga' });
                    }
                    return opts;
                }
            }" x-ref="filterForm" action="{{ route('riwayat.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           @input.debounce.500ms="$refs.filterForm.submit()"
                           placeholder="Nama balita..." 
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Kategori</label>
                    <select name="kategori" x-model="kategori" @change="hasilDefault = ''; $refs.hasilSelect.value = ''; $refs.filterForm.submit()" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">Semua Kategori</option>
                        <option value="kpsp">KPSP</option>
                        <option value="tdd">TDD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Hasil</label>
                    <select x-ref="hasilSelect" name="hasil" @change="$refs.filterForm.submit()" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition">
                        <option value="">Semua Hasil</option>
                        <template x-for="opt in hasilOptions" :key="opt.value">
                            <option :value="opt.value" x-text="opt.label" :selected="opt.value === hasilDefault"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Pemeriksaan</label>
                    <input type="date" name="tgl_pemeriksaan" value="{{ request('tgl_pemeriksaan') }}" 
                           @change="$refs.filterForm.submit()"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="bg-primary hover:bg-blue-600 text-white font-medium py-2.5 px-6 rounded-xl shadow-md transition w-full">
                        Filter
                    </button>
                    <a href="{{ route('riwayat.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2.5 px-6 rounded-xl transition text-center whitespace-nowrap">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Balita</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Umur</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Hasil</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($riwayat as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ \Carbon\Carbon::parse($item->tgl_pemeriksaan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-800">{{ $item->bayi->nama_bayi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $item->umur_saat_periksa_bulan }} bln
                        </td>
                        @php $jenisAktif = $item->hasil_kpsp ? 'kpsp' : 'tdd'; @endphp
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-slate-700 uppercase">
                            {{ $jenisAktif }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($jenisAktif === 'kpsp')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->hasil_kpsp == 'Sesuai (S)' ? 'bg-green-100 text-green-800' : ($item->hasil_kpsp == 'Meragukan (M)' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $item->hasil_kpsp }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->hasil_tdd == 'Normal' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->hasil_tdd }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('pemeriksaan.hasil', ['jenis' => $jenisAktif, 'pemeriksaan' => $item->id]) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors text-xs font-bold">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                                <a href="{{ route('riwayat.pdf', $item->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 transition-colors text-xs font-bold" target="_blank">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                            Belum ada data riwayat pemeriksaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
        <div class="bg-white px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</div>
@else
<!-- Admin/Nakes View -->
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Riwayat Pemeriksaan</h1>
            <p class="text-sm text-slate-500 mt-1">Lihat dan kelola riwayat pemeriksaan KPSP</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('riwayat.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 text-sm font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Reset Filter
            </a>
            @if(Auth::user()->role !== 'Orangtua')
            <a href="{{ route('riwayat.export') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-success/30 text-sm font-semibold rounded-xl text-success bg-success/5 hover:bg-success/10 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </a>
            @endif
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalPemeriksaan ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Total Pemeriksaan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-success/10 text-success flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalSesuai ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Sesuai Umur</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-warning/10 text-warning flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalMeragukan ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Meragukan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-danger/10 text-danger flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-800">{{ $totalRujukan ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Perlu Rujukan</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <form x-data="{
            kategori: '{{ request('kategori') }}',
            hasilDefault: '{{ request('hasil') }}',
            get hasilOptions() {
                let opts = [];
                if (this.kategori === '' || this.kategori === 'kpsp') {
                    opts.push({ value: 'Sesuai (S)', label: 'Sesuai (KPSP)' });
                    opts.push({ value: 'Meragukan (M)', label: 'Meragukan (KPSP)' });
                    opts.push({ value: 'Penyimpangan (P)', label: 'Penyimpangan (KPSP)' });
                }
                if (this.kategori === '' || this.kategori === 'tdd') {
                    opts.push({ value: 'Normal', label: 'Normal (TDD)' });
                    opts.push({ value: 'Curiga', label: 'Curiga (TDD)' });
                }
                return opts;
            }
        }" x-ref="filterForm" action="{{ route('riwayat.index') }}" method="GET" class="p-4 border-b border-slate-100 flex flex-col md:flex-row gap-4 items-center">
            <div class="relative w-full md:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" @input.debounce.500ms="$refs.filterForm.submit()" class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-primary focus:border-primary bg-slate-50 placeholder-slate-400" placeholder="Cari nama anak atau orang tua...">
            </div>
            <select name="bulan" @change="$refs.filterForm.submit()" class="w-full md:w-auto px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-primary focus:border-primary text-slate-600">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                @endforeach
            </select>
            <select name="tahun" @change="$refs.filterForm.submit()" class="w-full md:w-auto px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-primary focus:border-primary text-slate-600">
                <option value="">Semua Tahun</option>
                @foreach(range(now()->year, now()->year - 5) as $y)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="kategori" x-model="kategori" @change="hasilDefault = ''; $refs.hasilSelect.value = ''; $refs.filterForm.submit()" class="w-full md:w-auto px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-primary focus:border-primary text-slate-600">
                <option value="">Semua Kategori</option>
                <option value="kpsp">KPSP</option>
                <option value="tdd">TDD</option>
            </select>
            <select x-ref="hasilSelect" name="hasil" @change="$refs.filterForm.submit()" class="w-full md:w-auto px-4 py-2 border border-slate-200 rounded-lg text-sm bg-slate-50 focus:ring-primary focus:border-primary text-slate-600">
                <option value="">Semua Status</option>
                <template x-for="opt in hasilOptions" :key="opt.value">
                    <option :value="opt.value" x-text="opt.label" :selected="opt.value === hasilDefault"></option>
                </template>
            </select>
            <div class="md:ml-auto text-sm text-slate-500">
                <span>{{ $riwayat->total() }}</span> data
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">No</th>
                        <th class="py-4 px-6">Nama Anak</th>
                        <th class="py-4 px-6">Orang Tua</th>
                        <th class="py-4 px-6">Umur</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6">Kategori</th>
                        <th class="py-4 px-6">Skor</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($riwayat as $index => $item)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 text-slate-500">{{ $riwayat->firstItem() + $index }}</td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full {{ strpos($item->hasil_kpsp, 'Sesuai') !== false ? 'bg-success/10 text-success' : (strpos($item->hasil_kpsp, 'Meragukan') !== false ? 'bg-warning/10 text-warning' : 'bg-danger/10 text-danger') }} flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($item->bayi->nama_bayi, 0, 1) }}
                                    </div>
                                    {{ $item->bayi->nama_bayi }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-600">{{ $item->bayi->orangTua->nama_ortu ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $item->umur_saat_periksa_bulan }} Bulan</td>
                            <td class="py-4 px-6 text-slate-600">{{ \Carbon\Carbon::parse($item->tgl_pemeriksaan)->translatedFormat('d M Y') }}</td>
                            @php $jenisAktif = $item->hasil_kpsp ? 'kpsp' : 'tdd'; @endphp
                            <td class="py-4 px-6 font-bold text-slate-700 uppercase">{{ $jenisAktif }}</td>
                            <td class="py-4 px-6 font-medium text-slate-700">{{ $jenisAktif === 'kpsp' ? ($item->skor_kpsp ?? '-') . '/10' : ($item->skor_tdd ?? '-') }}</td>
                            <td class="py-4 px-6">
                                @if($jenisAktif === 'kpsp')
                                    @if(strpos($item->hasil_kpsp, 'Sesuai') !== false)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-success/10 text-success"><div class="w-1.5 h-1.5 rounded-full bg-success"></div> Sesuai Umur</span>
                                    @elseif(strpos($item->hasil_kpsp, 'Meragukan') !== false)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-warning/10 text-warning"><div class="w-1.5 h-1.5 rounded-full bg-warning"></div> Meragukan</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-danger/10 text-danger"><div class="w-1.5 h-1.5 rounded-full bg-danger"></div> Penyimpangan</span>
                                    @endif
                                @else
                                    @if($item->hasil_tdd === 'Normal')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-success/10 text-success"><div class="w-1.5 h-1.5 rounded-full bg-success"></div> Normal</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-danger/10 text-danger"><div class="w-1.5 h-1.5 rounded-full bg-danger"></div> {{ $item->hasil_tdd }}</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('pemeriksaan.hasil', ['jenis' => $jenisAktif, 'pemeriksaan' => $item->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary hover:bg-primary/20 transition-colors text-xs font-bold" title="Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                Belum ada data riwayat pemeriksaan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
        <div class="bg-white px-4 py-3 border-t border-slate-100 sm:px-6">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection
