<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .info-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #0891b2;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white !important;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #64748b;
            margin-top: 30px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">🚨 Admin Baru Mendaftar!</h1>
        <p style="margin: 10px 0 0 0;">Perlu Persetujuan Anda</p>
    </div>

    <div class="content">
        <h2>Halo Super Admin,</h2>
        
        <p>Ada admin baru yang mendaftar di sistem WebGIS Banjir Bantul dan memerlukan approval Anda.</p>

        <div class="info-box">
            <h3 style="margin-top: 0;">📋 Detail Admin Baru:</h3>
            <ul>
                <li><strong>Nama:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Waktu Registrasi:</strong> {{ $user->created_at->format('d F Y, H:i') }} WIB</li>
                <li><strong>Status:</strong> ⏳ Menunggu Approval</li>
            </ul>
        </div>

        <p><strong>Action Required:</strong></p>
        <p>Silakan login ke sistem untuk menyetujui atau menolak registrasi admin baru ini.</p>

        <center>
            <a href="{{ route('admin.users.index') }}" class="button">
                👉 Kelola Admin
            </a>
        </center>

        <p style="margin-top: 30px; color: #64748b; font-size: 0.9rem;">
            <strong>Note:</strong> Admin baru tidak dapat login sampai Anda menyetujui akun mereka.
        </p>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.</p>
            <p>&copy; {{ date('Y') }} BPBD Kabupaten Bantul. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
