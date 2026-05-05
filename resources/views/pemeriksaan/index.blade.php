@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8 text-center md:text-left">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Mulai Skrining {{ strtoupper($jenis) }} Anak</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih data anak dan isi informasi pemeriksaan</p>
    </div>

    <!-- Stepper -->
    <div class="flex items-center justify-start gap-2 sm:gap-4 mb-6 sm:mb-8 overflow-x-auto pb-2 -mx-4 px-4 sm:mx-0 sm:px-0">
        <div class="flex items-center gap-1.5 sm:gap-2 bg-success/10 text-success px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-bold whitespace-nowrap shrink-0">
            <span>1</span> Pilih Anak
        </div>
        <div class="w-6 sm:w-12 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 text-slate-400 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap shrink-0">
            <span>2</span> Kuesioner {{ strtoupper($jenis) }}
        </div>
        <div class="w-6 sm:w-12 h-px bg-slate-200 shrink-0"></div>
        <div class="flex items-center gap-1.5 sm:gap-2 text-slate-400 px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap shrink-0">
            <span>3</span> Hasil
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-success/10 text-success flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Data Pemeriksaan</h3>
                <p class="text-xs text-slate-500">Isi data anak yang akan diperiksa</p>
            </div>
        </div>

        <div class="p-8">
            <form action="{{ route('pemeriksaan.storeBayi', ['jenis' => $jenis]) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Data Anak <span class="text-danger">*</span></label>
                    <select name="bayi_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-primary focus:border-primary transition select2-bayi bg-slate-50">
                        <option value="">Cari nama anak atau orang tua...</option>
                        @foreach($bayis as $bayi)
                            @php
                                $tglLahir = \Carbon\Carbon::parse($bayi->tgl_lahir);
                                $diff = $tglLahir->diff(\Carbon\Carbon::now());
                                $umurBulan = ($diff->y * 12) + $diff->m;
                                if ($diff->d >= 16) $umurBulan += 1;
                                $umurText = floor($umurBulan / 12) > 0 ? floor($umurBulan / 12) . ' Th ' : '';
                                $umurText .= $umurBulan % 12 > 0 ? ($umurBulan % 12) . ' Bln' : '';
                                if ($umurBulan == 0) $umurText = '0 Bln';
                            @endphp
                            <option value="{{ $bayi->id }}" data-gender="{{ $bayi->jenis_kelamin }}" data-ortu="{{ $bayi->orangTua->nama_ortu ?? 'Tidak Diketahui' }}" data-umur="{{ $umurText }}">
                                {{ $bayi->nama_bayi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-slate-100">
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-1/2 text-center py-3 px-6 border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">
                        Kembali
                    </a>
                    <button type="submit" class="w-full sm:w-1/2 py-3 px-6 bg-success text-white rounded-xl font-bold hover:bg-success/90 transition-colors shadow-sm flex items-center justify-center gap-2">
                        Lanjut ke Kuesioner
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new TomSelect(".select2-bayi", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: "Cari nama anak atau orang tua...",
            render: {
                option: function(data, escape) {
                    const genderClass = data.gender === 'L' ? 'bg-blue-50 text-blue-500' : 'bg-pink-50 text-pink-500';
                    const genderIcon = data.gender === 'L' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>'; // simplifying icon
                    
                    return '<div class="flex items-center gap-4 py-2 px-3">' +
                                '<div class="w-10 h-10 rounded-full flex items-center justify-center ' + genderClass + '">' +
                                    '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">' + genderIcon + '</svg>' +
                                '</div>' +
                                '<div>' +
                                    '<div class="font-bold text-slate-800">' + escape(data.text) + '</div>' +
                                    '<div class="text-xs text-slate-500">' + escape(data.ortu) + ' · ' + escape(data.umur) + '</div>' +
                                '</div>' +
                           '</div>';
                },
                item: function(data, escape) {
                    return '<div class="flex items-center gap-2">' +
                                '<span class="font-bold text-slate-800">' + escape(data.text) + '</span>' +
                                '<span class="text-xs text-slate-500">(' + escape(data.umur) + ' - ' + escape(data.ortu) + ')</span>' +
                           '</div>';
                }
            }
        });
    });
</script>
@endsection
