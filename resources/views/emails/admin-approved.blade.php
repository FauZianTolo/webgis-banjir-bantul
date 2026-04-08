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
            background: linear-gradient(135deg, #10b981, #059669);
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
        .success-box {
            background: #d1fae5;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #10b981;
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
        .info-list {
            background: white;
            padding: 20px;
            border-radius: 8px;
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
        <h1 style="margin: 0;">✅ Akun Anda Telah Disetujui!</h1>
        <p style="margin: 10px 0 0 0;">Selamat Bergabung di Tim BPBD</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $user->name }}!</h2>
        
        <div class="success-box">
            <h3 style="margin-top: 0;">🎉 Selamat!</h3>
            <p style="margin: 0;">Akun admin Anda telah disetujui oleh Super Admin BPBD. Sekarang Anda dapat mengakses sistem WebGIS Banjir Bantul.</p>
        </div>

        <div class="info-list">
            <h3>📋 Informasi Akun:</h3>
            <ul>
                <li><strong>Nama:</strong> {{ $user->name }}</li>
                <li><strong>Email:</strong> {{ $user->email }}</li>
                <li><strong>Role:</strong> Admin BPBD</li>
                <li><strong>Status:</strong> ✅ Approved</li>
            </ul>
        </div>

        <p><strong>Langkah Selanjutnya:</strong></p>
        <ol>
            <li>Klik tombol "Login Sekarang" di bawah</li>
            <li>Masukkan email dan password yang Anda daftarkan</li>
            <li>Mulai kelola data laporan banjir</li>
        </ol>

        <center>
            <a href="{{ route('login') }}" class="button">
                🔐 Login Sekarang
            </a>
        </center>

        <div style="background: #e0f2fe; padding: 15px; border-radius: 8px; margin-top: 20px;">
            <p style="margin: 0; color: #0c4a6e;"><strong>💡 Tips:</strong></p>
            <p style="margin: 5px 0 0 0; color: #0c4a6e;">Jaga kerahasiaan password Anda dan jangan membagikan akses login kepada orang lain.</p>
        </div>

        <div class="footer">
            <p>Jika Anda memiliki pertanyaan, hubungi Super Admin BPBD.</p>
            <p>&copy; {{ date('Y') }} BPBD Kabupaten Bantul. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
