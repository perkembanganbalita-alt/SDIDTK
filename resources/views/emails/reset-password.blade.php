<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
        }
        .header {
            background-color: #0ea5e9;
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 40px;
        }
        .content p {
            margin: 0 0 20px 0;
            font-size: 15px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px 40px;
            text-align: center;
            font-size: 13px;
            color: #64748b;
            border-top: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Password PediatriCare</h1>
        </div>
        
        <div class="content">
            <p>Halo,</p>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun PediatriCare Anda.</p>
            
            <div class="btn-wrapper">
                <a href="{{ $resetUrl }}" class="btn">Reset Password Sekarang</a>
            </div>
            
            <p>Link reset password ini akan kedaluwarsa dalam 60 menit.</p>
            <p>Jika Anda tidak meminta reset password, Anda dapat mengabaikan email ini dengan aman.</p>
            
            <p style="margin-top: 40px; margin-bottom: 0;">Salam hangat,<br><strong>Tim PediatriCare</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} PediatriCare. Hak Cipta Dilindungi.</p>
            <p style="margin-top: 8px; font-size: 12px; color: #94a3b8;">
                Jika Anda kesulitan mengklik tombol "Reset Password Sekarang", silakan copy dan paste URL berikut ke browser Anda:<br>
                <a href="{{ $resetUrl }}" style="color: #0ea5e9;">{{ $resetUrl }}</a>
            </p>
        </div>
    </div>
</body>
</html>
