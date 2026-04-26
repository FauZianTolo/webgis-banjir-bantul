<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'WebGIS Banjir Bantul' }} - BPBD Bantul</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ==================== ROOT VARIABLES ==================== */
        :root {
            --primary-cyan: #0891b2;
            --primary-dark: #164e63;
            --primary-light: #22d3ee;
            --accent-orange: #f97316;
            --accent-green: #10b981;
            --accent-red: #ef4444;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-900: #0f172a;
        }

        /* ==================== GLOBAL RESET ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            color: #1e293b;
        }

        /* ==================== NAVBAR MODERN ==================== */
        .navbar-brand img {
            height: 50px;
            width: auto;
        }

        .navbar-brand img {
            filter: drop-shadow(0 0 10px rgba(34, 211, 238, 0.5));
            animation: pulse 4s ease-in-out infinite;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 50%, #06b6d4 100%);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(8, 145, 178, 0.3);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1050;
            border-bottom: 3px solid rgba(34, 211, 238, 0.3);
        }

        .navbar-custom .container-fluid {
            padding: 0.5rem 2rem;
        }

        .navbar-brand {
            font-weight: 900;
            font-size: 1.4rem;
            color: #ffffff !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand i {
            font-size: 1.8rem;
            color: #22d3ee;
            filter: drop-shadow(0 0 10px rgba(34, 211, 238, 0.5));
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .navbar-brand span {
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 0.75rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-nav {
            gap: 0.25rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.65rem 1.25rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
        }

        .nav-link i {
            font-size: 1rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        /* ==================== FOOTER ==================== */
        footer {
            background: linear-gradient(135deg, #0f172a 0%, #164e63 100%);
            color: rgba(255, 255, 255, 0.8);
            padding: 3rem 0 1rem;
            margin-top: 5rem;
            box-shadow: 0 -10px 40px rgba(8, 145, 178, 0.15);
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #22d3ee, #0891b2, #22d3ee);
        }

        footer h5 {
            color: #ffffff;
            font-weight: 700;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        footer h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: #22d3ee;
            border-radius: 3px;
        }

        footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            display: block;
            padding: 0.4rem 0;
        }

        footer a:hover {
            color: #22d3ee;
            transform: translateX(5px);
        }

        .social-links {
            display: flex;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: white;
            border: 2px solid rgba(34, 211, 238, 0.3);
        }

        .social-link:hover {
            background: #22d3ee;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(34, 211, 238, 0.4);
            border-color: white;
            color: white;
        }

        .footer-bottom {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            font-size: 0.9rem;
        }

        /* ==================== SCROLL TO TOP ==================== */
        #scrollToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0891b2, #22d3ee);
            color: white;
            border: none;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(8, 145, 178, 0.4);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        #scrollToTop.show {
            opacity: 1;
            visibility: visible;
        }

        #scrollToTop:hover {
            transform: translateY(-5px) scale(1.1);
            box-shadow: 0 12px 35px rgba(8, 145, 178, 0.6);
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .navbar-custom .container-fluid {
                padding: 0.5rem 1rem;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .navbar-brand i {
                font-size: 1.5rem;
            }

            .nav-link {
                padding: 0.5rem 1rem !important;
            }

            footer {
                text-align: center;
            }

            footer h5::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .social-links {
                justify-content: center;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"
                    style="height: 50px; width: auto; margin-right: 8px;">
                <span>BANTARA</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('peta') ? 'active' : '' }}" href="{{ route('peta') }}">
                            <i class="fas fa-map"></i> Peta Kerawanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('laporan') ? 'active' : '' }}" href="{{ route('laporan') }}">
                            <i class="fas fa-paper-plane"></i> Lapor Banjir
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('statistik') ? 'active' : '' }}"
                            href="{{ route('statistik') }}">
                            <i class="fas fa-chart-bar"></i> Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('berita') ? 'active' : '' }}" href="{{ route('berita') }}">
                            <i class="fas fa-newspaper"></i> Berita
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('kontak') ? 'active' : '' }}" href="{{ route('kontak') }}">
                            <i class="fas fa-phone"></i> Kontak
                        </a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}"
                                href="{{ route('login') }}">
                                <i class="fas fa-user"></i> Login
                            </a>
                        </li>
                    @endguest
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/*') || Request::is('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="position-relative">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-info-circle"></i> Tentang WebGIS</h5>
                    <p style="line-height: 1.8;">
                        Sistem Informasi Geografis untuk monitoring dan pelaporan kejadian banjir
                        di Kabupaten Bantul secara real-time.
                    </p>
                    <div class="social-links">
                        <a href="https://www.facebook.com/bpbdbantul" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/BPBDBantul" class="social-link" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/bpbdbantul" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@bpbdbantul" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-link"></i> Tautan Cepat</h5>
                    <a href="{{ route('peta') }}"><i class="fas fa-chevron-right"></i> Peta Kerawanan</a>
                    <a href="{{ route('laporan') }}"><i class="fas fa-chevron-right"></i> Lapor Banjir</a>
                    <a href="{{ route('statistik') }}"><i class="fas fa-chevron-right"></i> Statistik</a>
                    <a href="{{ route('berita') }}"><i class="fas fa-chevron-right"></i> Berita</a>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5><i class="fas fa-phone"></i> Kontak Darurat</h5>
                    <p><i class="fas fa-map-marker-alt"></i> BPBD Kabupaten Bantul<br>Yogyakarta</p>
                    <p><i class="fas fa-phone-alt"></i> (0274) 6462100</p>
                    <p><i class="fas fa-envelope"></i> bpbd@bantulkab.go.id</p>
                    <p
                        style="background: rgba(239, 68, 68, 0.2); padding: 0.75rem; border-radius: 8px; border-left: 4px solid #ef4444;">
                        <strong><i class="fas fa-exclamation-triangle"></i> Darurat:</strong> 112
                    </p>
                </div>
            </div>

            {{-- <!-- Tambahan: Logo Prodi & Universitas -->
            <div class="row justify-content-center mb-3">
                <div class="col-auto text-center">
                    <img src="{{ asset('images/sig.png') }}" alt="Logo Prodi"
                        style="height:80px; width:auto; margin-right:15px;">
                    <img src="{{ asset('images/ugm.png') }}" alt="Logo Universitas"
                        style="height:80px; width:auto;">
                </div>
            </div> --}}

            <div class="footer-bottom text-center">
                <p class="mb-0">
                    &copy; {{ date('Y') }} <strong>BANTARA</strong> |
                    WebGIS Banjir BPBD Kabupaten Bantul
                </p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scroll to Top Script -->
    <script>
        // Scroll to Top Button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scrollToTop');
            if (window.scrollY > 300) {
                scrollBtn.classList.add('show');
            } else {
                scrollBtn.classList.remove('show');
            }
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 8px 40px rgba(8, 145, 178, 0.5)';
            } else {
                navbar.style.boxShadow = '0 4px 30px rgba(8, 145, 178, 0.3)';
            }
        });
        // Auto-close navbar saat klik link menu di mobile
        document.querySelectorAll('#navbarNav .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const collapse = document.getElementById('navbarNav');
                if (collapse.classList.contains('show')) {
                    new bootstrap.Collapse(collapse).hide();
                }
            });
        });
    </script>

    @yield('script')

    @stack('scripts')
    <!-- ⭐⭐⭐ NOTIFICATION TOAST FOR ADMIN ⭐⭐⭐ -->
    @auth
        @include('components.notification-toast')
    @endauth
    @auth
        <script>
            function toggleNotificationsPublic() {
                window.location.href = '{{ route('admin.laporan.index') }}';
            }

            function updateNotifCountPublic() {
                fetch('/api/notifications/count')
                    .then(r => r.json())
                    .then(data => {
                        const badge = document.getElementById('notif-count-public');
                        if (badge && data.count > 0) {
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                            badge.style.display = 'flex';
                        } else if (badge) {
                            badge.style.display = 'none';
                        }
                    });
            }

            document.addEventListener('DOMContentLoaded', function() {
                updateNotifCountPublic();
                setInterval(updateNotifCountPublic, 15000);
            });
        </script>
    @endauth
</body>

</html>
