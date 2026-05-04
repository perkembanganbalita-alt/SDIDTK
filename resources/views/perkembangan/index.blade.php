@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="perkembanganFilter()">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Perkembangan Anak (0 - 60 Bulan)</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau tahapan perkembangan sesuai usia anak.</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Umur</label>
        <select x-model="selectedUmur" class="w-full md:w-1/3 border-slate-200 rounded-xl focus:ring-primary focus:border-primary shadow-sm text-slate-700 p-3">
            <option value="all">Semua Umur</option>
            @foreach($tahapans as $tahapan)
                <option value="{{ $tahapan->umur_min }}-{{ $tahapan->umur_max }}">Usia {{ $tahapan->umur_min }} - {{ $tahapan->umur_max }} Bulan</option>
            @endforeach
        </select>
    </div>

    <!-- Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($tahapans as $tahapan)
        @php
            $items = array_filter(array_map('trim', explode(';', $tahapan->perkembangan)));
        @endphp
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow" x-show="selectedUmur === 'all' || selectedUmur === '{{ $tahapan->umur_min }}-{{ $tahapan->umur_max }}'">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-6 bg-success/10 w-max px-4 py-2 rounded-full">
                    <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h2 class="text-sm font-bold text-success">Usia {{ $tahapan->umur_min }} - {{ $tahapan->umur_max }} Bulan</h2>
                </div>
                
                <ul class="space-y-4">
                    @foreach($items as $item)
                        @if(!empty($item))
                        <li class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-success/10 flex items-center justify-center text-success shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm text-slate-700 leading-relaxed">{{ $item }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
    </div>


</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('perkembanganFilter', () => ({
        selectedUmur: 'all'
    }))
})
</script>
@endsection
