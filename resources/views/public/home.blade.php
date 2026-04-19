@extends('layouts.public')

@section('styles')
    <style>
        /* ==================== HERO SECTION ==================== */
        .hero-section {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            position: relative;
            overflow: hidden;
            padding: 0;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(180deg, transparent 0%, #f0f9ff 100%);
        }

        .hero-content {
            padding-top: -5rem;
            /* atur tinggi dari navbar */
            padding-bottom: 3rem;
            position: relative;
            z-index: 2;
            padding: 10rem 0;
            color: white;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: fadeInDown 0.8s ease-out;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            text-shadow: 2px 4px 8px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 400;
            opacity: 0.95;
            margin-bottom: 2.5rem;
            line-height: 1.8;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .btn-hero-primary {
            background: white;
            color: #0891b2;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-hero-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
            color: #0891b2;
        }

        .btn-hero-outline {
            background: transparent;
            color: white;
            border: 3px solid white;
        }

        .btn-hero-outline:hover {
            background: white;
            color: #0891b2;
            transform: translateY(-5px);
        }

        .hero-image {
            position: relative;
            z-index: 2;
            animation: float 3s ease-in-out infinite;
        }

        .hero-image img {
            width: 100%;
            max-width: 500px;
            filter: drop-shadow(0 20px 60px rgba(0, 0, 0, 0.4));
        }

        .hero-img-anim {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-18px);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================== STATS SECTION ==================== */
        .stats-section {
            background: white;
            padding: 4rem 0;
            margin-top: -100px;
            position: relative;
            z-index: 3;
        }

        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #0891b2, #22d3ee);
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(8, 145, 178, 0.2);
            border-color: #0891b2;
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0891b2, #22d3ee);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(8, 145, 178, 0.3);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            color: #0891b2;
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 600;
            color: #64748b;
        }

        /* ==================== FEATURES SECTION ==================== */
        .features-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.8rem;
            font-weight: 900;
            color: #0c4a6e;
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(8, 145, 178, 0.05), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .feature-card:hover::before {
            right: -100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 60px rgba(8, 145, 178, 0.15);
            border-color: #0891b2;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #0891b2, #22d3ee);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 25px rgba(8, 145, 178, 0.3);
        }

        .feature-card h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0c4a6e;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #64748b;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .feature-link {
            color: #0891b2;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .feature-link:hover {
            gap: 1rem;
            color: #0c4a6e;
        }

        /* ==================== ABOUT SECTION ==================== */
        .about-section {
            padding: 5rem 0;
            background: white;
        }

        .about-accent {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(8, 145, 178, 0.1);
            color: #0891b2;
            padding: 0.5rem 1.1rem;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .about-title {
            font-size: 2.4rem;
            font-weight: 900;
            color: #0c4a6e;
            line-height: 1.2;
            margin-bottom: 1rem;
        }

        .about-text {
            color: #475569;
            line-height: 1.9;
            font-size: 1.05rem;
            margin-bottom: 1rem;
        }

        .about-mini {
            background: #f8fafc;
            border-radius: 14px;
            padding: 1.1rem 1.25rem;
            border: 1px solid rgba(8, 145, 178, 0.1);
            transition: all 0.3s;
            height: 100%;
        }

        .about-mini:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(8, 145, 178, 0.12);
            border-color: #0891b2;
        }

        .about-mini i {
            font-size: 1.4rem;
            color: #0891b2;
            margin-bottom: 0.6rem;
            display: block;
        }

        /* ==================== REPORTS SECTION ==================== */
        .report-card {
            cursor: pointer;
            position: relative;
        }

        .report-card .foto-wrap {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 1rem;
        }

        .report-card .foto-wrap img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            transition: transform 0.3s;
            cursor: zoom-in;
        }

        .report-card:hover .foto-wrap img {
            transform: scale(1.04);
        }

        .report-card .foto-zoom-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.55);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 4px 8px;
            font-size: 11px;
            cursor: pointer;
            backdrop-filter: blur(4px);
        }

        .btn-lihat-peta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 0.75rem;
            text-decoration: none;
            width: 100%;
            justify-content: center;
        }

        .btn-lihat-peta:hover {
            background: linear-gradient(135deg, #0e7490, #0891b2);
            transform: translateY(-2px);
            color: white;
        }

        /* ==================== ZOOM MODAL ==================== */
        #zoomModalHome {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.92);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(6px);
        }

        .zoom-close-home {
            position: absolute;
            top: -18px;
            right: -18px;
            background: #ef4444;
            color: white;
            border: 4px solid white;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            line-height: 1;
        }

        .zoom-close-home:hover {
            background: #dc2626;
            transform: scale(1.15) rotate(90deg);
        }

        /* ==================== CTA SECTION ==================== */
        .cta-section {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 100%);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: white;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .cta-content p {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ==================== CONTACT / PESAN SECTION ==================== */
        .pesan-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        }

        .pesan-box {
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(8, 145, 178, 0.1);
            overflow: hidden;
            border: 2px solid rgba(8, 145, 178, 0.1);
        }

        .pesan-header {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            padding: 2rem 2.5rem;
        }

        .pesan-header h3 {
            font-weight: 900;
            font-size: 1.6rem;
            margin: 0 0 0.5rem;
        }

        .pesan-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        .pesan-body {
            padding: 2.5rem;
        }

        .pesan-input {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.85rem 1.2rem;
            font-size: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
            outline: none;
            font-family: inherit;
        }

        .pesan-input:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
        }

        .pesan-input::placeholder {
            color: #94a3b8;
        }

        .btn-pesan {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(8, 145, 178, 0.3);
        }

        .btn-pesan:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(8, 145, 178, 0.5);
        }

        .pesan-info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 12px;
            background: #f8fafc;
            margin-bottom: 0.75rem;
            border: 1px solid #e2e8f0;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
        }

        .pesan-info-item:hover {
            background: #e0f2fe;
            border-color: #0891b2;
            transform: translateX(5px);
            color: inherit;
        }

        .pesan-info-icon {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.1rem;
        }

        .pesan-info-text {
            flex: 1;
        }

        .pesan-info-text strong {
            display: block;
            color: #0c4a6e;
            font-size: 0.85rem;
        }

        .pesan-info-text span {
            color: #475569;
            font-size: 0.95rem;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-image {
                margin-top: 3rem;
                text-align: center;
            }

            .section-title h2 {
                font-size: 2.2rem;
            }

            .cta-content h2 {
                font-size: 2rem;
            }

            .about-title {
                font-size: 2rem;
            }
        }

        .laporan-section {
            margin-top: 80px;
            padding-top: 40px;
        }

        .btn-lihat-peta {
            position: relative;
            z-index: 10;
        }

        /* ===== MOBILE RESPONSIVE TAMBAHAN (tidak menghapus apapun di atas) ===== */
        @media (max-width: 991px) {
            .hero-content {
                padding: 6rem 0 3rem;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-image {
                margin-top: 2rem;
            }

            .hero-img-anim {
                max-height: 300px;
            }
        }

        @media (max-width: 767px) {
            .hero-section {
                min-height: auto;
            }

            .hero-content {
                padding: 5rem 0 2rem;
                text-align: center;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .hero-buttons {
                justify-content: center;
            }

            .hero-image {
                display: none;
            }

            /* sembunyikan ilustrasi di HP kecil */

            /* Fitur unggulan */
            .features-section {
                padding: 2.5rem 0;
            }

            .feature-card {
                padding: 1.2rem;
                border-radius: 14px;
                margin-bottom: 0;
            }

            .feature-icon {
                width: 48px;
                height: 48px;
                font-size: 1.3rem;
                margin-bottom: 0.75rem;
            }

            .feature-card h4 {
                font-size: 0.95rem;
                margin-bottom: 0.4rem;
            }

            .feature-card p {
                font-size: 0.82rem;
                margin-bottom: 0.6rem;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .feature-link {
                font-size: 0.8rem;
            }

            .features-section .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            /* Laporan terbaru */
            .laporan-section .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .report-card .foto-wrap img {
                height: 160px;
            }

            .report-card h4 {
                font-size: 1rem;
            }

            .report-card p {
                font-size: 0.88rem;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.7rem;
            }

            .features-section .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .feature-card {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-start hero-content">
                <div class="col-lg-6">
                    <div class="hero-badge">
                        <i class="fas fa-shield-alt"></i> Sistem Monitoring dan Pelaporan
                    </div>

                    <h1 class="hero-title">
                        Bantul Tanggap<br>
                        <span style="color: #22d3ee;">Rawan Bencana Banjir</span>
                    </h1>

                    <p class="hero-subtitle">
                        Sistem Informasi Geografis untuk monitoring dan pelaporan kejadian banjir
                        secara cepat di Kabupaten Bantul.
                    </p>

                    <div class="hero-buttons">
                        <a href="{{ route('peta') }}" class="btn-hero btn-hero-primary">
                            <i class="fas fa-map-marked-alt"></i>
                            Lihat Peta
                        </a>
                        <a href="{{ route('laporan') }}" class="btn-hero btn-hero-outline">
                            <i class="fas fa-paper-plane"></i>
                            Lapor Banjir
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="hero-image text-center">
                        <img src="{{ asset('images/maskot.png') }}" alt="WebGIS Bantul" class="hero-img-anim"
                            style="max-width:100%;height:auto;max-height:420px;filter:drop-shadow(0 20px 60px rgba(0,0,0,0.25));">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="stat-number">{{ $totalLaporan }}</div>
                        <div class="stat-label">Total Laporan</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-map-marked-alt"></i></div>
                        <div class="stat-number">17</div>
                        <div class="stat-label">Kecamatan</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-number">{{ $totalLaporan > 0 ? $totalLaporan * 3 : 0 }}</div>
                        <div class="stat-label">Pelapor Aktif</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Monitoring</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Fitur Unggulan</h2>
                <p>Sistem monitoring banjir yang lengkap dan mudah digunakan</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <a href="{{ route('peta') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-map-marked-alt"></i></div>
                            <h4>Peta Interaktif</h4>
                            <p>Lihat zona kerawanan banjir secara visual dengan peta interaktif berbasis GIS. Tampilan
                                real-time untuk monitoring area rawan banjir.</p>
                            <span class="feature-link">Lihat Peta <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('laporan') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-paper-plane"></i></div>
                            <h4>Lapor Banjir</h4>
                            <p>Laporkan kejadian banjir secara cepat dengan foto, lokasi GPS, dan deskripsi lengkap. Bantu
                                BPBD dalam penanganan darurat.</p>
                            <span class="feature-link">Buat Laporan <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('statistik') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                            <h4>Statistik & Analisis</h4>
                            <p>Analisis data historis banjir dengan grafik dan statistik lengkap. Identifikasi pola dan tren
                                kejadian banjir.</p>
                            <span class="feature-link">Lihat Statistik <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('berita') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-cloud-sun-rain"></i></div>
                            <h4>Info Cuaca</h4>
                            <p>Prakiraan cuaca real-time untuk antisipasi potensi banjir. Data cuaca akurat dari satelit
                                meteorologi.</p>
                            <span class="feature-link">Lihat Cuaca <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('berita') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-bell"></i></div>
                            <h4>Peringatan Dini</h4>
                            <p>Sistem notifikasi otomatis untuk peringatan dini banjir. Tetap waspada dengan alert
                                real-time.</p>
                            <span class="feature-link">Info Peringatan <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="{{ route('peta') }}" style="text-decoration:none;display:block;height:100%;">
                        <div class="feature-card h-100">
                            <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                            <h4>Akses Mobile</h4>
                            <p>Akses sistem dari mana saja melalui smartphone. Responsive design untuk pengalaman optimal di
                                semua device.</p>
                            <span class="feature-link">Coba Sekarang <i class="fas fa-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="about-accent">
                        <i class="fas fa-water"></i> WebGIS Partisipatif Banjir
                    </div>
                    <h2 class="about-title">
                        Apa itu <span style="color:#0891b2;">BANTARA</span>?
                    </h2>
                    <p class="about-text">
                        <strong style="color:#0891b2;">BANTARA</strong> (Bantul Tanggap Rawan Bencana Banjir) adalah
                        platform
                        WebGIS berbasis peta interaktif yang dirancang khusus untuk membantu masyarakat dan
                        pemerintah Kabupaten Bantul dalam menghadapi ancaman bencana banjir.
                    </p>
                    <p class="about-text">
                        Sistem ini menghubungkan masyarakat dengan informasi kerawanan banjir secara spasial,
                        memungkinkan pelaporan kejadian secara partisipatif, sehingga penanganan oleh BPBD
                        dapat dilakukan lebih cepat, tepat, dan berbasis data lapangan yang akurat.
                    </p>
                    <p style="font-weight:800;color:#0c4a6e;font-size:1.1rem;margin-top:1rem;">
                        🌊 Bersama BANTARA, Waspada Banjir, Selamatkan Bantul.
                    </p>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <a href="{{ route('peta') }}" class="btn btn-primary"
                            style="background:#0891b2;border:none;padding:.8rem 1.8rem;font-weight:700;border-radius:10px;">
                            <i class="fas fa-map-marked-alt"></i> Buka Peta
                        </a>
                        <a href="{{ route('laporan') }}" class="btn btn-outline-primary"
                            style="border-color:#0891b2;color:#0891b2;padding:.8rem 1.8rem;font-weight:700;border-radius:10px;">
                            <i class="fas fa-paper-plane"></i> Lapor Banjir
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-map"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Peta Kerawanan</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Zona rawan banjir
                                    divisualisasikan secara spasial dan interaktif.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-users"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Pelaporan Partisipatif</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Masyarakat dapat
                                    melaporkan kejadian banjir langsung dari sistem.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-bell"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Monitoring Real-time</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Laporan terbaru membantu
                                    pemantauan kejadian banjir secara cepat.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-shield-alt"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Mitigasi Bencana</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Mendukung kesiapsiagaan
                                    dan pengambilan keputusan BPBD Bantul.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-database"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Data Historis BPBD</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Tersedia data historis
                                    kejadian banjir 2020–2025 Kab. Bantul.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="about-mini">
                                <i class="fas fa-graduation-cap"></i>
                                <h6 style="font-weight:800;color:#0c4a6e;margin-bottom:.4rem;">Riset & TA UGM</h6>
                                <p style="color:#64748b;font-size:.9rem;margin:0;line-height:1.6;">Dikembangkan sebagai
                                    Tugas Akhir Program Studi SIG Sekolah Vokasi UGM.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Laporan Terbaru -->
    @if ($laporanTerbaru->count() > 0)
        <section class="features-section laporan-section">
            <div class="container">
                <div class="section-title">
                    <h2>Laporan Terbaru</h2>
                    <p>Kejadian banjir yang baru dilaporkan — klik foto untuk memperbesar, klik "Lihat di Peta" untuk menuju
                        lokasi</p>
                </div>

                <div class="row g-4">
                    @foreach ($laporanTerbaru as $laporan)
                        @php
                            $badgeColor =
                                $laporan->kedalaman_cm >= 70
                                    ? '#ef4444'
                                    : ($laporan->kedalaman_cm >= 40
                                        ? '#f59e0b'
                                        : '#0891b2');
                        @endphp
                        <div class="col-md-4">
                            <div class="feature-card report-card">
                                @if ($laporan->foto)
                                    <div class="foto-wrap">
                                        <img src="{{ asset('uploads/laporan/' . $laporan->foto) }}" alt="Laporan Banjir"
                                            onclick="openZoomHome('{{ asset('uploads/laporan/' . $laporan->foto) }}')">
                                        <button type="button" class="foto-zoom-btn"
                                            onclick="openZoomHome('{{ asset('uploads/laporan/' . $laporan->foto) }}')">
                                            <i class="fas fa-search-plus"></i> Perbesar
                                        </button>
                                    </div>
                                @endif

                                <h4>{{ $laporan->kecamatan }}</h4>
                                <p>
                                    <strong>Lokasi:</strong> {{ $laporan->desa }}<br>
                                    <strong>Kedalaman:</strong>
                                    <span class="badge"
                                        style="background: {{ $badgeColor }}; color: white; padding: 3px 9px; border-radius: 7px;">
                                        {{ $laporan->kedalaman_cm }} cm
                                    </span><br>
                                    <strong>Waktu:</strong> {{ $laporan->waktu_laporan->diffForHumans() }}
                                </p>
                                <p style="font-size:.95rem;color:#64748b;">
                                    {{ \Illuminate\Support\Str::limit($laporan->deskripsi, 100) }}
                                </p>

                                <a href="{{ route('peta') }}" class="btn-lihat-peta"
                                    onclick="sessionStorage.setItem('bantara_focus_laporan', '{{ $laporan->id }}')">
                                    <i class="fas fa-map-marker-alt"></i> Lihat di Peta
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Pesan & Konsultasi -->
    <section class="pesan-section">
        <div class="container">
            <div class="section-title">
                <h2>Tanya & Konsultasi</h2>
                <p>Punya pertanyaan, saran, atau ingin berkonsultasi seputar BANTARA dan bencana banjir?</p>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4">
                    <div style="height:100%;">
                        <h5 style="font-weight:800;color:#0c4a6e;margin-bottom:1.5rem;">
                            <i class="fas fa-headset" style="color:#0891b2;"></i> Hubungi Kami
                        </h5>

                        <a href="tel:+620274367319" class="pesan-info-item">
                            <div class="pesan-info-icon"><i class="fas fa-phone"></i></div>
                            <div class="pesan-info-text">
                                <strong>Telepon BPBD Bantul</strong>
                                <span>(0274) 367319</span>
                            </div>
                        </a>

                        <a href="https://wa.me/620274367319" target="_blank" class="pesan-info-item">
                            <div class="pesan-info-icon" style="background:linear-gradient(135deg,#25D366,#128C7E);">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="pesan-info-text">
                                <strong>WhatsApp BPBD</strong>
                                <span>Chat langsung via WhatsApp</span>
                            </div>
                        </a>

                        <a href="mailto:bpbd@bantulkab.go.id" class="pesan-info-item">
                            <div class="pesan-info-icon" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="pesan-info-text">
                                <strong>Email</strong>
                                <span>bpbd@bantulkab.go.id</span>
                            </div>
                        </a>

                        <a href="{{ url('/kontak') }}" class="pesan-info-item">
                            <div class="pesan-info-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="pesan-info-text">
                                <strong>Lihat Lokasi Kantor</strong>
                                <span>Jl. Lingkar Timur, Bantul</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="pesan-box">
                        <div class="pesan-header">
                            <h3><i class="fas fa-comment-dots"></i> Kirim Pesan</h3>
                            <p>Isi formulir di bawah ini — kami akan merespons sesegera mungkin</p>
                        </div>
                        <div class="pesan-body">
                            <div id="pesanSuccess"
                                style="display:none;background:#d1fae5;border-left:4px solid #10b981;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1rem;color:#065f46;font-weight:600;">
                                <i class="fas fa-check-circle"></i> Pesan berhasil terkirim! Kami akan segera menghubungi
                                Anda.
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="pesan-input" id="pesanNama"
                                        placeholder="Nama Lengkap *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="pesan-input" id="pesanKontak"
                                        placeholder="No. Telepon / WhatsApp *" required>
                                </div>
                                <div class="col-12">
                                    <select class="pesan-input" id="pesanTopik" style="cursor:pointer;">
                                        <option value="">-- Topik Pertanyaan --</option>
                                        <option>Cara melaporkan kejadian banjir</option>
                                        <option>Informasi zona kerawanan banjir</option>
                                        <option>Bantuan teknis penggunaan sistem</option>
                                        <option>Saran dan masukan untuk BANTARA</option>
                                        <option>Konsultasi penanganan darurat</option>
                                        <option>Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <textarea class="pesan-input" id="pesanIsi" rows="4"
                                        placeholder="Tulis pertanyaan atau saran Anda di sini..." style="resize:vertical;"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn-pesan w-100" onclick="kirimPesan()">
                                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Zoom Modal -->
    <div id="zoomModalHome" onclick="closeZoomHome()">
        <div style="position:relative;max-width:90vw;max-height:90vh;" onclick="event.stopPropagation()">
            <img id="zoomImgHome" src="" alt="Foto"
                style="max-width:90vw;max-height:85vh;border-radius:14px;box-shadow:0 20px 80px rgba(0,0,0,0.6);object-fit:contain;">
            <button type="button" class="zoom-close-home" onclick="closeZoomHome()">×</button>
        </div>
    </div>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>
                    <i class="fas fa-exclamation-circle"></i>
                    Punya Informasi Banjir?
                </h2>
                <p>
                    Bantu kami melakukan monitoring dengan melaporkan kejadian banjir di sekitar Anda.
                    Laporan Anda sangat berharga untuk penanganan cepat dan akurat.
                </p>
                <div class="hero-buttons" style="justify-content: center;">
                    <a href="{{ route('laporan') }}" class="btn-hero btn-hero-primary">
                        <i class="fas fa-paper-plane"></i>
                        Lapor Sekarang
                    </a>
                    <a href="{{ route('peta') }}" class="btn-hero btn-hero-outline">
                        <i class="fas fa-map"></i>
                        Lihat Peta
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function openZoomHome(src) {
            document.getElementById('zoomImgHome').src = src;
            const modal = document.getElementById('zoomModalHome');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeZoomHome() {
            document.getElementById('zoomModalHome').style.display = 'none';
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeZoomHome();
        });

        async function kirimPesan() {

            const nama = document.getElementById('pesanNama').value.trim();
            const kontak = document.getElementById('pesanKontak').value.trim();
            const isi = document.getElementById('pesanIsi').value.trim();
            const topik = document.getElementById('pesanTopik').value;

            if (!nama || !kontak || !isi) {
                alert("Mohon isi semua data terlebih dahulu");
                return;
            }

            const data = {
                nama: nama,
                kontak: kontak,
                topik: topik,
                pesan: isi
            };

            const url =
                "https://script.google.com/macros/s/AKfycbwAxW1Xt-juC0mz_pORNYwPsUsspKYABbuNRGPyJ1XOKv_tnSqIN6nuKBOtpynN-jwzsg/exec";

            try {

                await fetch(url, {
                    method: "POST",
                    body: JSON.stringify(data)
                });

                document.getElementById('pesanSuccess').style.display = 'block';

                document.getElementById('pesanNama').value = '';
                document.getElementById('pesanKontak').value = '';
                document.getElementById('pesanIsi').value = '';
                document.getElementById('pesanTopik').value = '';

            } catch (err) {
                alert("Gagal mengirim pesan");
            }
        }
    </script>
@endsection
