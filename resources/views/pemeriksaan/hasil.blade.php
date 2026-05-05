@extends('layouts.app')

@section('content')
@php
    $bayi = $pemeriksaan->bayi;
    $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
    $diff = $tglLahir->diff(\Carbon\Carbon::now());
    $umurBulan = ($diff->y * 12) + $diff->m;
    if ($diff->d >= 16) $umurBulan += 1;
    $umurText = floor($umurBulan / 12) > 0 ? floor($umurBulan / 12) . ' Th ' : '';
    $umurText .= $umurBulan % 12 > 0 ? ($umurBulan % 12) . ' Bln' : '';
    if ($umurBulan == 0) $umurText = '0 Bln';

    $isSesuai = false;
    $isMeragukan = false;
    $isPenyimpangan = false;

    if ($jenis === 'kpsp') {
        $isSesuai = strpos($pemeriksaan->hasil_kpsp, 'Sesuai') !== false;
        $isMeragukan = strpos($pemeriksaan->hasil_kpsp, 'Meragukan') !== false;
        $isPenyimpangan = strpos($pemeriksaan->hasil_kpsp, 'Penyimpangan') !== false;
        
        if($isSesuai) {
            $colorClass = 'success';
            $bgClass = 'bg-success/10 border-success/20';
            $textClass = 'text-success';
            $desc = 'Perkembangan anak sesuai dengan tahapan umurnya. Teruskan pola asuh dan stimulasi saat ini.';
        } elseif($isMeragukan) {
            $colorClass = 'warning';
            $bgClass = 'bg-warning/10 border-warning/20';
            $textClass = 'text-warning';
            $desc = 'Perkembangan anak meragukan. Lakukan stimulasi lebih intensif dan evaluasi ulang dalam 2 minggu.';
        } else {
            $colorClass = 'danger';
            $bgClass = 'bg-danger/10 border-danger/20';
            $textClass = 'text-danger';
            $desc = 'Perkembangan anak menunjukkan keterlambatan signifikan. Segera rujuk ke fasilitas kesehatan.';
        }
    } elseif ($jenis === 'tdd') {
        $isSesuai = $pemeriksaan->hasil_tdd == 'Normal';
        $isMeragukan = $pemeriksaan->hasil_tdd == 'Curiga';
        
        if($isSesuai) {
            $colorClass = 'success';
            $bgClass = 'bg-success/10 border-success/20';
            $textClass = 'text-success';
            $desc = 'Daya dengar anak normal. Teruskan stimulasi perkembangan komunikasi.';
        } else {
            $colorClass = 'danger';
            $bgClass = 'bg-danger/10 border-danger/20';
            $textClass = 'text-danger';
            $desc = 'Anak dicurigai mengalami gangguan daya dengar. Lakukan evaluasi lebih lanjut atau rujuk ke dokter.';
        }
    }
@endphp

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Hasil Skrining Anak</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan hasil kuesioner {{ strtoupper($jenis) }}</p>
    </div>

    <!-- Stepper -->
    <div class="flex items-center justify-start gap-2 sm:gap-4 mb-6 sm:mb-8 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold whitespace-nowrap shrink-0">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Pilih Anak
        </div>
        <div class="w-6 sm:w-8 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold whitespace-nowrap shrink-0">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Kuesioner {{ strtoupper($jenis) }}
        </div>
        <div class="w-6 sm:w-8 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold shadow-sm ring-1 ring-success/20 whitespace-nowrap shrink-0">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Hasil
        </div>
    </div>

    <!-- Result Box -->
    <div class="{{ $bgClass }} rounded-3xl border p-8 mb-8">
        <div class="flex items-start gap-4 mb-8">
            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center {{ $textClass }} shrink-0">
                @if($isSesuai)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                @elseif($isMeragukan)
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                @else
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                @endif
            </div>
            <div>
                <h2 class="text-lg font-bold flex items-center gap-2 {{ $textClass }}">
                    <span class="bg-white/50 px-2 py-0.5 rounded text-sm">{{ $jenis === 'kpsp' ? explode(' ', $pemeriksaan->hasil_kpsp)[0] : $pemeriksaan->hasil_tdd }}</span> 
                    <span class="text-slate-800">{{ strtoupper($jenis) }} - Usia {{ $umurText }}</span>
                </h2>
                <p class="{{ $textClass }} opacity-90 mt-1 font-medium">{{ $desc }}</p>
            </div>
        </div>

        @if($jenis === 'kpsp')
        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-8">
            <div class="bg-white rounded-2xl p-2 sm:p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-success mb-1">{{ $pemeriksaan->skor_kpsp }}</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-tight sm:tracking-wide">Skor Ya</div>
            </div>
            <div class="bg-white rounded-2xl p-2 sm:p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-danger mb-1">{{ 10 - $pemeriksaan->skor_kpsp }}</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-tight sm:tracking-wide">Skor Tidak</div>
            </div>
            <div class="bg-white rounded-2xl p-2 sm:p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-1">{{ $pemeriksaan->skor_kpsp }}/10</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-tight sm:tracking-wide">Total Skor</div>
            </div>
        </div>
        @elseif($jenis === 'tdd')
        <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-8">
            <div class="bg-white rounded-2xl p-2 sm:p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-danger mb-1">{{ $pemeriksaan->skor_tdd }}</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-tight sm:tracking-wide">Jawaban Tidak</div>
            </div>
            <div class="bg-white rounded-2xl p-2 sm:p-4 shadow-sm border border-slate-100 text-center">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-1">{{ count($pemeriksaan->tdd_jawabans) }}</div>
                <div class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-tight sm:tracking-wide">Total Pertanyaan</div>
            </div>
        </div>
        @endif

        <div class="bg-white/50 rounded-2xl p-6 grid grid-cols-2 sm:grid-cols-3 gap-y-6 gap-x-4">
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Nama Anak</p>
                <p class="text-sm font-bold text-slate-800">{{ $bayi->nama_bayi }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Umur</p>
                <p class="text-sm font-bold text-slate-800">{{ $umurText }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Tanggal Pemeriksaan</p>
                <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Orang Tua</p>
                <p class="text-sm font-bold text-slate-800">{{ $bayi->orangTua->nama_ortu ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Jenis Kelamin</p>
                <p class="text-sm font-bold text-slate-800">{{ $bayi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium mb-1">Kader</p>
                <p class="text-sm font-bold text-slate-800">{{ $pemeriksaan->nakes->name ?? Auth::user()->name }}</p>
            </div>
        </div>
    </div>

    <!-- Rekomendasi Tindak Lanjut -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
            Rekomendasi Tindak Lanjut
        </h3>
        
        <ul class="space-y-4">
            @if($isSesuai)
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">1</span>
                    <span class="text-slate-700 mt-0.5">Berikan pujian kepada orang tua atau pengasuh karena pola asuh yang sudah baik.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">2</span>
                    <span class="text-slate-700 mt-0.5">Lanjutkan stimulasi tahapan umur bulan berikutnya.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">3</span>
                    <span class="text-slate-700 mt-0.5">Jadwalkan kunjungan berikutnya secara rutin sesuai usia anak.</span>
                </li>
            @elseif($isMeragukan)
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">1</span>
                    <span class="text-slate-700 mt-0.5">Nasehati ibu atau pengasuh untuk melakukan stimulasi lebih sering dengan penuh kasih sayang.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">2</span>
                    <span class="text-slate-700 mt-0.5">Ajarkan ibu cara melakukan intervensi dini khusus pada sektor perkembangan yang mendapat jawaban TIDAK.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">3</span>
                    <span class="text-slate-700 mt-0.5">Jadwalkan kunjungan ulang 2 minggu lagi untuk evaluasi intensif.</span>
                </li>
            @else
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">1</span>
                    <span class="text-slate-700 mt-0.5">Segera rujuk ke fasilitas kesehatan untuk pemeriksaan lebih lanjut.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">2</span>
                    <span class="text-slate-700 mt-0.5">Berikan edukasi kepada orang tua tentang pentingnya rujukan.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">3</span>
                    <span class="text-slate-700 mt-0.5">Dokumentasikan hasil pemeriksaan dengan lengkap.</span>
                </li>
                <li class="flex items-start gap-4 p-4 rounded-2xl bg-slate-50">
                    <span class="w-6 h-6 rounded-full bg-white shadow-sm flex items-center justify-center text-xs font-bold text-slate-600 shrink-0">4</span>
                    <span class="text-slate-700 mt-0.5">Koordinasi dengan petugas kesehatan untuk tindak lanjut.</span>
                </li>
            @endif
        </ul>
    </div>

    <!-- Detail Jawaban -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 mb-8">
        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Detail Jawaban
        </h3>
        
        <div class="space-y-4">
            @if($jenis === 'kpsp')
                @foreach($pemeriksaan->kpsp_jawabans as $index => $jawab)
                <div class="flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="font-bold text-slate-400 mt-0.5">{{ $index + 1 }}</div>
                    <div class="flex-grow">
                        <p class="text-slate-700 mb-1 pr-12">{{ $jawab->pertanyaan }}</p>
                        <p class="text-xs text-slate-400">{{ $jawab->sub_kategori }}</p>
                    </div>
                    <div class="shrink-0">
                        @if($jawab->jawaban == 'Ya')
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-success/10 text-success text-xs font-bold w-14">Ya</span>
                        @else
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-danger/10 text-danger text-xs font-bold w-14">Tidak</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @elseif($jenis === 'tdd')
                @foreach($pemeriksaan->tdd_jawabans as $index => $jawab)
                <div class="flex items-start gap-4 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                    <div class="font-bold text-slate-400 mt-0.5">TDD {{ $index + 1 }}</div>
                    <div class="flex-grow">
                        <p class="text-slate-700 mb-1 pr-12">{{ $jawab->pertanyaan }}</p>
                        <p class="text-xs text-slate-400">Tes Daya Dengar</p>
                    </div>
                    <div class="shrink-0">
                        @if($jawab->jawaban == 'Ya')
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-success/10 text-success text-xs font-bold w-14">Ya</span>
                        @else
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded bg-danger/10 text-danger text-xs font-bold w-14">Tidak</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>

    @if($tahapanToShow && $jenis === 'kpsp')
    @php
        if (!function_exists('formatTahapanText')) {
            function formatTahapanText($text, $colorClass = 'text-slate-600') {
                if (!$text) return '-';
                $sections = array_filter(array_map('trim', explode('.', $text)));
                $html = '<div class="space-y-3 text-sm ' . $colorClass . '">';
                foreach ($sections as $section) {
                    if (strpos($section, ':') !== false) {
                        list($cat, $items) = explode(':', $section, 2);
                        $html .= '<div><strong class="block font-semibold mb-1 opacity-90">'.trim($cat).'</strong>';
                        $html .= '<ul class="list-disc pl-4 space-y-1 opacity-90">';
                        $itemsArr = array_filter(array_map('trim', explode(';', $items)));
                        foreach($itemsArr as $item) {
                            $html .= '<li>'.trim($item).'</li>';
                        }
                        $html .= '</ul></div>';
                    } else {
                        $html .= '<ul class="list-disc pl-4 space-y-1 opacity-90">';
                        $itemsArr = array_filter(array_map('trim', explode(';', $section)));
                        foreach($itemsArr as $item) {
                            $html .= '<li>'.trim($item).'</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                $html .= '</div>';
                return $html;
            }
        }
    @endphp
    
    <!-- Panduan Stimulasi -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="p-6 bg-primary/10 border-b border-primary/20">
            <h2 class="text-xl font-bold text-primary flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Panduan Stimulasi Anak
                </div>
                <span class="text-sm bg-white text-primary px-3 py-1 rounded-full shadow-sm">Umur: {{ $tahapanToShow->umur_min }}-{{ $tahapanToShow->umur_max }} Bulan</span>
            </h2>
            @if($isSesuai)
            <p class="mt-2 text-sm text-slate-700 font-medium bg-white/50 p-2 rounded-lg inline-block">Karena perkembangan anak sesuai dengan umurnya, panduan stimulasi di bawah ini adalah untuk <span class="font-bold">tahapan usia selanjutnya</span>.</p>
            @endif
        </div>
        <div class="p-8">
            {!! formatTahapanText($tahapanToShow->stimulasi, 'text-slate-700') !!}
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between items-center bg-white p-4 rounded-3xl shadow-sm border border-slate-100">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-2 px-6 py-3 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-colors w-full sm:w-auto">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Kembali Dashboard
        </a>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <a href="{{ route('riwayat.pdf', $pemeriksaan->id) }}" class="flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 shadow-sm transition-all w-full sm:w-auto">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak PDF
            </a>
        </div>
    </div>
</div>
@endsection
