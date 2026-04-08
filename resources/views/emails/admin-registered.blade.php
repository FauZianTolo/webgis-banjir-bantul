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
            background: linear-gradient(135deg, #0891b2, #06b6d4);
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
            border-left: 4px solid #f59e0b;
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
        <h1 style="margin: 0;">🌊 WebGIS Banjir Bantul</h1>
        <p style="margin: 10px 0 0 0;">BPBD Kabupaten Bantul</p>
    </div>

    <div class="content">
        <h2>Halo, {{ $user->name }}!</h2>
        
        <p>Terima kasih telah mendaftar sebagai Admin BPBD di sistem WebGIS Banjir Bantul.</p>

        <div class="info-box">
            <h3 style="margin-top: 0;">⏳ Status Akun: Menunggu Approval</h3>
            <p>Akun Anda saat ini sedang dalam proses verifikasi oleh Super Admin BPBD.</p>
            <p><strong>Detail Akun:</strong></p>
            <ul>
                <li>Nama: {{ $user->name }}</li>
                <li>Email: {{ $user->email }}</li>
                <li>Waktu Registrasi: {{ $user->created_at->format('d F Y, H:i') }} WIB</li>
            </ul>
        </div>

        <p><strong>Langkah Selanjutnya:</strong></p>
        <ol>
            <li>Tim Super Admin akan meninjau data Anda</li>
            <li>Anda akan menerima email notifikasi setelah akun disetujui</li>
            <li>Setelah disetujui, Anda dapat login ke sistem</li>
        </ol>

        <p>Jika Anda memiliki pertanyaan, silakan hubungi Super Admin BPBD.</p>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem.</p>
            <p>&copy; {{ date('Y') }} BPBD Kabupaten Bantul. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
