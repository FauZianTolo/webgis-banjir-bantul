<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menunggu Approval - WebGIS Banjir Bantul</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .pending-container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 30px 100px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-container {
            margin-bottom: 2rem;
            position: relative;
        }

        .icon-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 5px solid #f59e0b;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.7);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(245, 158, 11, 0);
            }
        }

        .icon-circle i {
            font-size: 4rem;
            color: #f59e0b;
        }

        h1 {
            color: #0c4a6e;
            font-weight: 900;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .subtitle {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .info-box {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            padding: 1.5rem;
            border-radius: 15px;
            margin: 2rem 0;
            border-left: 5px solid #0891b2;
        }

        .info-box h3 {
            color: #0c4a6e;
            font-weight: 800;
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .info-box p {
            color: #475569;
            line-height: 1.6;
            margin: 0;
        }

        .steps {
            text-align: left;
            margin: 2rem 0;
        }

        .step {
            display: flex;
            align-items: start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .step-number {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex-shrink: 0;
        }

        .step-content h4 {
            color: #0c4a6e;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .step-content p {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
        }

        .btn-back {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 15px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 2rem;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(8, 145, 178, 0.3);
        }

        .contact-info {
            background: #fef3c7;
            padding: 1.25rem;
            border-radius: 12px;
            margin-top: 2rem;
            border: 2px solid #f59e0b;
        }

        .contact-info p {
            color: #92400e;
            font-weight: 600;
            margin: 0;
        }

        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #f59e0b;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 0.5rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div class="pending-container">
        <div class="icon-container">
            <div class="icon-circle">
                <i class="fas fa-clock"></i>
            </div>
        </div>

        <h1>Menunggu Persetujuan</h1>
        <p class="subtitle">
            <span class="spinner"></span>
            Akun Anda sedang dalam proses verifikasi oleh Super Admin BPBD Bantul
        </p>

        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> Status Pendaftaran</h3>
            <p>Registrasi Anda telah berhasil! Kami telah mengirimkan email konfirmasi ke alamat email Anda. Silakan
                periksa inbox (dan folder spam) Anda.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Email Konfirmasi Terkirim</h4>
                    <p>Kami telah mengirim email ke akun Anda dengan detail pendaftaran</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Menunggu Verifikasi Admin</h4>
                    <p>Super Admin akan meninjau dan memverifikasi data Anda</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Notifikasi Persetujuan</h4>
                    <p>Anda akan menerima email saat akun disetujui dan siap digunakan</p>
                </div>
            </div>
        </div>

        <div class="contact-info">
            <p><i class="fas fa-envelope"></i> <strong>Butuh bantuan?</strong> Hubungi Super Admin BPBD melalui email
                resmi</p>
        </div>

        <a href="{{ route('home') }}" class="btn-back">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
    <script>
        // Auto-check approval status setiap 10 detik
        setInterval(function() {
            fetch('/check-approval-status')
                .then(response => response.json())
                .then(data => {
                    if (data.approved) {
                        // Redirect ke login dengan pesan sukses
                        window.location.href = '/login?approved=true';
                    }
                })
                .catch(error => console.log('Checking status...'));
        }, 10000); // Check every 10 seconds
    </script>
</body>

</html>
