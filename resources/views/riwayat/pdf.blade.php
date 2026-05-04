<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Skrining - {{ $pemeriksaan->bayi->nama_bayi }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #0ea5e9; padding-bottom: 15px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #0ea5e9; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 12px; }
        .row { margin-bottom: 20px; }
        .col-6 { width: 48%; display: inline-block; vertical-align: top; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #e2e8f0; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; }
        .card { border: 1px solid #e2e8f0; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .card-title { font-weight: bold; font-size: 16px; margin-top: 0; margin-bottom: 10px; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }
        .text-danger { color: #ef4444; font-weight: bold; }
        .text-success { color: #10b981; font-weight: bold; }
        .text-warning { color: #f59e0b; font-weight: bold; }
        .bg-danger-light { background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>PEDIATRICARE - HASIL SKRINING SDIDTK</h1>
        <p>Tanggal Pemeriksaan: {{ \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan)->format('d F Y') }}</p>
    </div>

    @if($jenis === 'kpsp' && $pemeriksaan->hasil_kpsp == 'Penyimpangan (P)')
    <div class="bg-danger-light">
        <strong>PERINGATAN DARURAT: RUJUKAN SEGERA</strong><br>
        Berdasarkan hasil skrining, ditemukan indikasi kemungkinan penyimpangan perkembangan. Segera rujuk anak ke Rumah Sakit Rujukan Tumbuh Kembang Level 1.
    </div>
    @elseif($jenis === 'tdd' && $pemeriksaan->hasil_tdd == 'Curiga')
    <div class="bg-danger-light">
        <strong>PERINGATAN DARURAT: RUJUKAN SEGERA</strong><br>
        Berdasarkan hasil skrining, ditemukan indikasi curiga gangguan pendengaran. Segera rujuk anak ke fasilitas kesehatan untuk pemeriksaan lebih lanjut.
    </div>
    @endif

    <div class="row">
        <div class="col-6">
            <div class="card">
                <h3 class="card-title">Data Balita</h3>
                <p><strong>Nama:</strong> {{ $pemeriksaan->bayi->nama_bayi }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($pemeriksaan->bayi->tgl_lahir)->format('d/m/Y') }}</p>
                <p><strong>Jenis Kelamin:</strong> {{ $pemeriksaan->bayi->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                @php
                    $tglLahir = \Carbon\Carbon::parse($pemeriksaan->bayi->tgl_lahir);
                    $tglPeriksa = \Carbon\Carbon::parse($pemeriksaan->tgl_pemeriksaan);
                    $diff = $tglLahir->diff($tglPeriksa);
                    $umurBulanExact = ($diff->y * 12) + $diff->m;
                    if ($diff->d >= 16) $umurBulanExact += 1;
                @endphp
                <p><strong>Umur Bayi Saat Ini (Bulan):</strong> {{ $umurBulanExact }} Bulan</p>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <h3 class="card-title">Data Pemeriksaan</h3>
                <p><strong>Nama Orang Tua:</strong> {{ $pemeriksaan->bayi->orangTua->nama_ortu }}</p>
                <p><strong>Pemeriksa (Nakes):</strong> {{ optional($pemeriksaan->nakes)->name ?? '-' }}</p>
                @if($jenis === 'kpsp')
                <p><strong>Skor KPSP:</strong> {{ $pemeriksaan->skor_kpsp }} / 10</p>
                <p><strong>Hasil KPSP:</strong> <span class="{{ $pemeriksaan->hasil_kpsp == 'Sesuai (S)' ? 'text-success' : ($pemeriksaan->hasil_kpsp == 'Meragukan (M)' ? 'text-warning' : 'text-danger') }}">{{ $pemeriksaan->hasil_kpsp }}</span></p>
                @elseif($jenis === 'tdd')
                <p><strong>Hasil TDD:</strong> <span class="{{ $pemeriksaan->hasil_tdd == 'Normal' ? 'text-success' : 'text-danger' }}">{{ $pemeriksaan->hasil_tdd }}</span></p>
                @endif
            </div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Intervensi & Edukasi (Berdasarkan {{ strtoupper($jenis) }})</h3>
        
        @if($jenis === 'kpsp')
        @php
            $jawabanTidak = $pemeriksaan->kpsp_jawabans->where('jawaban', 'Tidak');
        @endphp
        
        @if($jawabanTidak->count() > 0)
        <div style="background-color: #fffbeb; border: 1px solid #fde68a; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <strong style="color: #d97706;">Perhatian: Terdapat {{ $jawabanTidak->count() }} Aspek yang Perlu Ditingkatkan (Jawaban TIDAK):</strong>
            <ul style="margin-top: 5px; margin-bottom: 0;">
                @foreach($jawabanTidak as $jawab)
                <li style="margin-bottom: 5px;">
                    <strong>[{{ $jawab->sub_kategori }}]</strong> {{ $jawab->pertanyaan }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @endif
        
        @if($jenis === 'tdd')
        @php
            $jawabanTidakTDD = $pemeriksaan->tdd_jawabans->where('jawaban', 'Tidak');
        @endphp
        
        @if($jawabanTidakTDD->count() > 0)
        <div style="background-color: #fef2f2; border: 1px solid #fca5a5; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <strong style="color: #b91c1c;">Perhatian: Terdapat {{ $jawabanTidakTDD->count() }} Masalah pada Tes Daya Dengar (Jawaban TIDAK):</strong>
            <ul style="margin-top: 5px; margin-bottom: 0;">
                @foreach($jawabanTidakTDD as $jawab)
                <li style="margin-bottom: 5px;">
                    <strong>[Pendengaran]</strong> {{ $jawab->pertanyaan }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @endif

        <ul>
            @if($jenis === 'kpsp')
                @if($pemeriksaan->hasil_kpsp == 'Sesuai (S)')
                    <li>Berikan pujian kepada orang tua atau pengasuh karena pola asuh yang sudah baik.</li>
                    <li>Lanjutkan stimulasi tahapan umur bulan berikutnya.</li>
                    <li>Jadwalkan kunjungan berikutnya secara rutin.</li>
                    <li>Edukasi orang tua untuk mempertahankan pemantauan rutin di rumah.</li>
                @elseif($pemeriksaan->hasil_kpsp == 'Meragukan (M)')
                    <li>Nasehati ibu atau pengasuh untuk melakukan stimulasi lebih sering dengan penuh kasih sayang.</li>
                    <li>Ajarkan ibu cara melakukan intervensi dini khusus pada sektor perkembangan yang tertinggal.</li>
                    <li><b>Jadwalkan kunjungan ulang 2 minggu lagi untuk evaluasi intensif.</b></li>
                    <li>Jika hasil pemeriksaan selanjutnya masih meragukan, rujuk ke Rumah Sakit.</li>
                @else
                    <li class="text-danger">Rujuk ke RS rujukan tumbuh kembang level 1.</li>
                @endif
            @elseif($jenis === 'tdd')
                @if($pemeriksaan->hasil_tdd == 'Normal')
                    <li>Daya dengar anak normal. Teruskan stimulasi perkembangan komunikasi.</li>
                @else
                    <li class="text-danger">Anak dicurigai mengalami gangguan daya dengar. Lakukan evaluasi lebih lanjut atau rujuk ke dokter spesialis THT.</li>
                @endif
            @endif
        </ul>
    </div>

    @if($tahapanToShow && $jenis === 'kpsp')
    @php
        if (!function_exists('formatTahapanTextPdf')) {
            function formatTahapanTextPdf($text) {
                if (!$text) return '-';
                $sections = array_filter(array_map('trim', explode('.', $text)));
                $html = '';
                foreach ($sections as $section) {
                    if (strpos($section, ':') !== false) {
                        list($cat, $items) = explode(':', $section, 2);
                        $html .= '<div style="margin-bottom: 8px;"><strong>'.trim($cat).'</strong>';
                        $html .= '<ul style="margin-top: 2px; margin-bottom: 0; padding-left: 20px;">';
                        $itemsArr = array_filter(array_map('trim', explode(';', $items)));
                        foreach($itemsArr as $item) {
                            $html .= '<li style="margin-bottom: 3px;">'.trim($item).'</li>';
                        }
                        $html .= '</ul></div>';
                    } else {
                        $html .= '<ul style="margin-top: 2px; margin-bottom: 8px; padding-left: 20px;">';
                        $itemsArr = array_filter(array_map('trim', explode(';', $section)));
                        foreach($itemsArr as $item) {
                            $html .= '<li style="margin-bottom: 3px;">'.trim($item).'</li>';
                        }
                        $html .= '</ul>';
                    }
                }
                return $html;
            }
        }
    @endphp
    
    <div class="card">
        <h3 class="card-title" style="background-color: #0ea5e9; color: white; padding: 10px; margin: -15px -15px 15px -15px; border-radius: 5px 5px 0 0;">
            Panduan Stimulasi Anak (Umur {{ $tahapanToShow->umur_min }}-{{ $tahapanToShow->umur_max }} Bulan)
        </h3>
        
        @if($jenis === 'kpsp' && strpos($pemeriksaan->hasil_kpsp, 'Sesuai') !== false)
        <p style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 8px; border-radius: 4px; margin-bottom: 15px; font-weight: bold;">
            Karena perkembangan anak sesuai dengan umurnya, panduan stimulasi di bawah ini adalah untuk tahapan usia selanjutnya.
        </p>
        @endif
        
        <div style="font-size: 13px; line-height: 1.6;">
            {!! formatTahapanTextPdf($tahapanToShow->stimulasi) !!}
        </div>
    </div>
    @endif

</body>
</html>
