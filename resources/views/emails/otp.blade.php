<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Aktivasi Akun - PediatriCare</title>
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
                                        <!-- Logo Icon -->
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
                            <h2 style="margin: 0 0 8px; color: #1e293b; font-size: 22px; font-weight: 700;">Verifikasi Akun Anda</h2>
                            <p style="margin: 0 0 24px; color: #64748b; font-size: 15px; line-height: 1.6;">
                                Terima kasih telah mendaftar di <strong style="color: #0ea5e9;">PediatriCare</strong>. Untuk menyelesaikan proses pendaftaran, masukkan kode OTP berikut pada halaman verifikasi:
                            </p>

                            <!-- OTP Code Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 2px dashed #0ea5e9; border-radius: 12px; padding: 24px 32px; display: inline-block;">
                                            <p style="margin: 0 0 8px; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; font-weight: 600;">Kode OTP Anda</p>
                                            <p style="margin: 0; color: #0369a1; font-size: 40px; font-weight: 800; letter-spacing: 8px; font-family: 'Courier New', Courier, monospace;">{{ $otpCode }}</p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Timer Notice -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top: 24px;">
                                <tr>
                                    <td align="center">
                                        <div style="background-color: #fefce8; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 20px; display: inline-block;">
                                            <span style="color: #a16207; font-size: 14px;">⏱️ Kode ini berlaku selama <strong>10 menit</strong></span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 28px 0;">

                            <!-- Security Notice -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="background-color: #fef2f2; border-radius: 8px; padding: 16px;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="vertical-align: top; padding-right: 12px;">
                                                    <span style="font-size: 20px;">🔒</span>
                                                </td>
                                                <td>
                                                    <p style="margin: 0; color: #991b1b; font-size: 13px; font-weight: 600;">Peringatan Keamanan</p>
                                                    <p style="margin: 4px 0 0; color: #b91c1c; font-size: 13px; line-height: 1.5;">
                                                        Jangan pernah membagikan kode OTP ini kepada siapa pun, termasuk pihak yang mengaku sebagai tim PediatriCare. Kami tidak pernah meminta kode OTP melalui telepon atau pesan.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 24px 0 0; color: #94a3b8; font-size: 13px; line-height: 1.5;">
                                Jika Anda tidak merasa mendaftar di PediatriCare, abaikan email ini.
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
