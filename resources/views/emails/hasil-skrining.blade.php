<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Skrining {{ strtoupper($jenis) }} - PediatriCare</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f0f4f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <!-- Main Container -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%); padding: 40px 40px 30px; text-align: center;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="width: 60px; height: 60px; background-color: rgba(255,255,255,0.2); border-radius: 16px; margin: 0 auto 16px; line-height: 60px; font-size: 28px;">
                                            🩺
                                        </div>
                                        <h1 style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 700; letter-spacing: -0.5px;">PediatriCare</h1>
                                        <p style="margin: 8px 0 0; color: rgba(255,255,255,0.85); font-size: 14px; font-weight: 400;">Posyandu Digital — SDIDTK</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Greeting -->
                            <h2 style="margin: 0 0 8px; color: #1e293b; font-size: 22px; font-weight: 700;">Hasil Skrining {{ strtoupper($jenis) }}</h2>
                            <p style="margin: 0 0 24px; color: #64748b; font-size: 15px; line-height: 1.6;">
                                Yth. Bapak/Ibu <strong style="color: #1e293b;">{{ $namaOrtu }}</strong>,
                            </p>
                            <p style="margin: 0 0 28px; color: #475569; font-size: 15px; line-height: 1.7;">
                                Berikut kami sampaikan hasil skrining perkembangan <strong>({{ strtoupper($jenis) }})</strong> untuk ananda <strong style="color: #0ea5e9;">{{ $namaBayi }}</strong> yang telah dilaksanakan pada tanggal <strong>{{ $tanggalPemeriksaan }}</strong>.
                            </p>

                            <!-- Result Card -->
                            @php
                                if ($jenis === 'kpsp') {
                                    $hasil = $hasilSkrining;
                                    if (str_contains($hasil, 'Sesuai')) {
                                        $bgColor = '#f0fdf4'; $borderColor = '#22c55e'; $textColor = '#166534'; $iconBg = '#dcfce7'; $icon = '✅'; $label = 'Sesuai';
                                    } elseif (str_contains($hasil, 'Meragukan')) {
                                        $bgColor = '#fffbeb'; $borderColor = '#f59e0b'; $textColor = '#92400e'; $iconBg = '#fef3c7'; $icon = '⚠️'; $label = 'Meragukan';
                                    } else {
                                        $bgColor = '#fef2f2'; $borderColor = '#ef4444'; $textColor = '#991b1b'; $iconBg = '#fee2e2'; $icon = '🔴'; $label = 'Penyimpangan';
                                    }
                                } else {
                                    $hasil = $hasilSkrining;
                                    if ($hasil === 'Normal') {
                                        $bgColor = '#f0fdf4'; $borderColor = '#22c55e'; $textColor = '#166534'; $iconBg = '#dcfce7'; $icon = '✅'; $label = 'Normal';
                                    } else {
                                        $bgColor = '#fef2f2'; $borderColor = '#ef4444'; $textColor = '#991b1b'; $iconBg = '#fee2e2'; $icon = '🔴'; $label = 'Curiga';
                                    }
                                }
                            @endphp

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: {{ $bgColor }}; border-left: 4px solid {{ $borderColor }}; border-radius: 0 12px 12px 0; padding: 20px 24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align: middle; width: 50px;">
                                                    <div style="width: 44px; height: 44px; background-color: {{ $iconBg }}; border-radius: 50%; text-align: center; line-height: 44px; font-size: 22px;">
                                                        {{ $icon }}
                                                    </div>
                                                </td>
                                                <td style="vertical-align: middle; padding-left: 16px;">
                                                    <p style="margin: 0; color: {{ $textColor }}; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;">Hasil Skrining {{ strtoupper($jenis) }}</p>
                                                    <p style="margin: 4px 0 0; color: {{ $textColor }}; font-size: 20px; font-weight: 800;">{{ $label }}</p>
                                                    @if($jenis === 'kpsp')
                                                    <p style="margin: 4px 0 0; color: {{ $textColor }}; font-size: 13px; opacity: 0.8;">Skor: {{ $skorSkrining }} / 10</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Info Summary -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; border-radius: 12px; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <p style="margin: 0 0 12px; color: #475569; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Ringkasan Pemeriksaan</p>
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 14px; width: 45%;">Nama Anak</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 14px; font-weight: 600;">{{ $namaBayi }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 14px; border-top: 1px solid #e2e8f0;">Usia Saat Skrining</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 14px; font-weight: 600; border-top: 1px solid #e2e8f0;">{{ $umurSkrining }} Bulan</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 14px; border-top: 1px solid #e2e8f0;">Tanggal Pemeriksaan</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 14px; font-weight: 600; border-top: 1px solid #e2e8f0;">{{ $tanggalPemeriksaan }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-size: 14px; border-top: 1px solid #e2e8f0;">Pemeriksa</td>
                                                <td style="padding: 6px 0; color: #1e293b; font-size: 14px; font-weight: 600; border-top: 1px solid #e2e8f0;">{{ $namaNakes }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Attachment Notice -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px 20px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <span style="font-size: 20px;">📎</span>
                                                </td>
                                                <td>
                                                    <p style="margin: 0; color: #1e40af; font-size: 14px; font-weight: 600;">Dokumen Terlampir</p>
                                                    <p style="margin: 4px 0 0; color: #3b82f6; font-size: 13px; line-height: 1.5;">
                                                        Hasil skrining lengkap beserta rekomendasi stimulasi terlampir dalam format PDF. Silakan unduh dan simpan untuk keperluan dokumentasi.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Recommendation -->
                            @php
                                if ($jenis === 'kpsp') {
                                    if (str_contains($hasilSkrining, 'Sesuai')) {
                                        $rekomendasiIcon = '🌟';
                                        $rekomendasiBg = '#f0fdf4';
                                        $rekomendasiBorder = '#bbf7d0';
                                        $rekomendasiColor = '#166534';
                                        $rekomendasiText = 'Perkembangan anak sesuai dengan usianya. Teruskan stimulasi dan pantau perkembangan secara rutin.';
                                    } elseif (str_contains($hasilSkrining, 'Meragukan')) {
                                        $rekomendasiIcon = '📋';
                                        $rekomendasiBg = '#fffbeb';
                                        $rekomendasiBorder = '#fde68a';
                                        $rekomendasiColor = '#92400e';
                                        $rekomendasiText = 'Diperlukan stimulasi lebih intensif. Jadwalkan kunjungan ulang 2 minggu lagi untuk evaluasi perkembangan.';
                                    } else {
                                        $rekomendasiIcon = '🏥';
                                        $rekomendasiBg = '#fef2f2';
                                        $rekomendasiBorder = '#fecaca';
                                        $rekomendasiColor = '#991b1b';
                                        $rekomendasiText = 'Ditemukan indikasi penyimpangan perkembangan. Silakan segera kunjungi fasilitas kesehatan untuk pemeriksaan lebih lanjut.';
                                    }
                                } else {
                                    if ($hasilSkrining === 'Normal') {
                                        $rekomendasiIcon = '🌟';
                                        $rekomendasiBg = '#f0fdf4';
                                        $rekomendasiBorder = '#bbf7d0';
                                        $rekomendasiColor = '#166534';
                                        $rekomendasiText = 'Daya dengar anak normal. Teruskan stimulasi perkembangan komunikasi dan pantau secara rutin.';
                                    } else {
                                        $rekomendasiIcon = '🏥';
                                        $rekomendasiBg = '#fef2f2';
                                        $rekomendasiBorder = '#fecaca';
                                        $rekomendasiColor = '#991b1b';
                                        $rekomendasiText = 'Terdapat kecurigaan gangguan daya dengar. Silakan segera konsultasikan ke dokter spesialis THT.';
                                    }
                                }
                            @endphp

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: {{ $rekomendasiBg }}; border: 1px solid {{ $rekomendasiBorder }}; border-radius: 12px; padding: 16px 20px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <span style="font-size: 20px;">{{ $rekomendasiIcon }}</span>
                                                </td>
                                                <td>
                                                    <p style="margin: 0; color: {{ $rekomendasiColor }}; font-size: 14px; font-weight: 600;">Rekomendasi</p>
                                                    <p style="margin: 4px 0 0; color: {{ $rekomendasiColor }}; font-size: 13px; line-height: 1.5;">
                                                        {{ $rekomendasiText }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 28px 0;">

                            <!-- Closing -->
                            <p style="margin: 0; color: #475569; font-size: 14px; line-height: 1.7;">
                                Apabila Bapak/Ibu memiliki pertanyaan terkait hasil skrining ini, silakan menghubungi petugas kesehatan (Nakes) di Posyandu terdekat.
                            </p>
                            <p style="margin: 16px 0 0; color: #475569; font-size: 14px; line-height: 1.7;">
                                Terima kasih atas kepercayaan Bapak/Ibu menggunakan layanan PediatriCare. 🙏
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 24px 40px; border-top: 1px solid #e2e8f0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 4px; color: #0ea5e9; font-size: 15px; font-weight: 700;">PediatriCare</p>
                                        <p style="margin: 0 0 12px; color: #94a3b8; font-size: 12px;">Posyandu Digital — Stimulasi, Deteksi, dan Intervensi Dini Tumbuh Kembang</p>
                                        <p style="margin: 0 0 4px; color: #cbd5e1; font-size: 11px;">
                                            Email ini dikirim secara otomatis oleh sistem PediatriCare.
                                        </p>
                                        <p style="margin: 0; color: #cbd5e1; font-size: 11px;">
                                            &copy; {{ date('Y') }} PediatriCare. Hak cipta dilindungi undang-undang.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
