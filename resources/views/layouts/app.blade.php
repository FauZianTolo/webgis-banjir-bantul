<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        /* ==================== GLOBAL VARIABLES ==================== */
        :root {
            --admin-primary: #0891b2;
            --admin-dark: #0c4a6e;
            --admin-light: #06b6d4;
            --sidebar-width: 280px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
        }

        /* ==================== TOP NAVBAR ==================== */
        .admin-topbar {
            background: linear-gradient(135deg, #0c4a6e 0%, #0891b2 100%);
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            box-shadow: 0 4px 30px rgba(8, 145, 178, 0.3);
            border-bottom: 3px solid rgba(34, 211, 238, 0.3);
        }

        .topbar-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 2rem;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: white !important;
            font-weight: 900;
            font-size: 1.5rem;
            text-decoration: none;
        }

        .admin-brand i {
            font-size: 2rem;
            color: #22d3ee;
            filter: drop-shadow(0 0 10px rgba(34, 211, 238, 0.5));
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .topbar-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-link:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .user-menu {
            position: relative;
        }

        .user-menu-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .user-menu-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }

        .user-menu.show .user-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-dropdown-header {
            padding: 1.25rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .user-dropdown-item {
            padding: 1rem 1.25rem;
            color: #334155;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .user-dropdown-item:hover {
            background: #f0f9ff;
            color: #0891b2;
        }

        /* ==================== SIDEBAR ==================== */
        .admin-sidebar {
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: var(--sidebar-width);
            height: calc(100vh - var(--topbar-height));
            background: white;
            box-shadow: 4px 0 30px rgba(8, 145, 178, 0.1);
            overflow-y: auto;
            z-index: 1040;
            border-right: 3px solid rgba(8, 145, 178, 0.1);
        }

        .sidebar-nav {
            padding: 1.5rem 0;
        }

        .nav-section-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: #475569;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link i {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            color: #0891b2;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: white;
            box-shadow: 0 5px 20px rgba(8, 145, 178, 0.3);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background: white;
            border-radius: 0 4px 4px 0;
        }

        /* ==================== MAIN CONTENT ==================== */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 2rem;
            min-height: calc(100vh - var(--topbar-height));
        }

        /* ==================== MOBILE RESPONSIVE ==================== */
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0;
                padding: 1rem;
            }
            .topbar-content {
                padding: 0 0.75rem;
            }
            .admin-brand span {
                display: none;
            }
            .mobile-menu-toggle {
                display: block !important;
            }
        }
        /* ── MOBILE GLOBAL HEADER CARD ── */
        @media (max-width: 767px) {
            .admin-main {
                padding: 0.75rem;
            }
            .admin-main > .mb-4 > div {
                padding: 1rem 1.25rem !important;
                border-radius: 14px !important;
            }
            .admin-main > .mb-4 > div h2 {
                font-size: 1.25rem !important;
                line-height: 1.3;
            }
            .admin-main > .mb-4 > div p {
                font-size: 0.82rem !important;
                margin-top: 0.3rem !important;
            }
        }

        .mobile-menu-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.6rem 1rem;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.25rem;
        }

        .mobile-menu-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: var(--topbar-height);
            left: 0;
            width: 100%;
            height: calc(100vh - var(--topbar-height));
            background: rgba(0, 0, 0, 0.5);
            z-index: 1035;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ==================== NOTIFICATION BELL ==================== */
        .notification-bell {
            position: relative;
        }

        .bell-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .bell-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.05);
        }

        .bell-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            border: 2px solid white;
            animation: bellPulse 2s infinite;
        }

        @keyframes bellPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .notification-dropdown {
            position: absolute;
            top: calc(100% + 15px);
            right: 0;
            width: 360px;
            max-height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 9999;
            border: 2px solid #e2e8f0;
        }
        @media (max-width: 600px) {
            .notification-dropdown {
                width: calc(100vw - 20px);
                right: -5px;
            }
        }

        .notification-bell.show .notification-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .notification-header {
            padding: 1.25rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .notification-header h6 {
            margin: 0;
            font-weight: 800;
            color: #0c4a6e;
        }

        .mark-all-btn {
            background: none;
            border: none;
            color: #0891b2;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
        }

        .mark-all-btn:hover {
            text-decoration: underline;
        }

        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f0f9ff;
        }

        .notification-item.unread {
            background: #f0f9ff;
            border-left: 4px solid #0891b2;
        }

        .notification-title {
            font-weight: 700;
            color: #0c4a6e;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            font-size: 0.85rem;
            color: #64748b;
            margin-bottom: 0.25rem;
        }

        .notification-time {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .notification-footer {
            padding: 1rem;
            text-align: center;
            border-top: 2px solid #e2e8f0;
        }

        .notification-footer a {
            color: #0891b2;
            font-weight: 700;
            text-decoration: none;
        }

        .notification-footer a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Top Navbar -->
    <nav class="admin-topbar">
        <div class="topbar-content">
            <div class="topbar-left">
                <button class="mobile-menu-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>

                <a href="{{ route('dashboard') }}" class="admin-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                        style="height: 50px; width: auto; margin-right: 8px;">
                    <span>BANTARA - (Admin)</span>
                </a>
            </div>

            <div class="topbar-right">
                <a href="{{ route('home') }}" class="topbar-link">
                    <i class="fas fa-globe"></i>
                    <span class="d-none d-md-inline">Lihat Public</span>
                </a>

                <!-- ⭐⭐⭐ BELL ICON NOTIFICATION ⭐⭐⭐ -->
                <div class="notification-bell">
                    <button class="bell-btn" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                        <span class="bell-badge" id="notif-count" style="display: none;">0</span>
                    </button>

                    <div class="notification-dropdown">
                        <div class="notification-header">
                            <h6>Notifikasi</h6>
                            <button onclick="markAllRead()" class="mark-all-btn">Tandai dibaca</button>
                        </div>
                        <div class="notification-list" id="notification-list">
                            <div style="padding: 2rem; text-align: center; color: #94a3b8;">
                                <div class="spinner-border spinner-border-sm mb-2"></div>
                                <div>Memuat notifikasi...</div>
                            </div>
                        </div>
                        <div class="notification-footer">
                            <a href="{{ route('admin.laporan.index') }}">Lihat Semua Laporan</a>
                        </div>
                    </div>
                </div>

                <div class="user-menu">
                    <button class="user-menu-btn" onclick="toggleUserMenu()">
                        <i class="fas fa-user-circle"></i>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                    </button>

                    <div class="user-dropdown">
                        <div class="user-dropdown-header">
                            <div style="font-weight: 800; color: #0c4a6e;">{{ Auth::user()->name }}</div>
                            <div style="font-size: 0.85rem; color: #64748b;">{{ Auth::user()->email }}</div>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                            <i class="fas fa-user-edit"></i>
                            Profile Settings
                        </a>

                        <a href="{{ route('dashboard') }}" class="user-dropdown-item">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>

                        <div style="height: 1px; background: #e2e8f0; margin: 0.5rem 0;"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="user-dropdown-item" style="color: #ef4444;">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar">
        <nav class="sidebar-nav">
            <div class="nav-section-title">Menu Utama</div>

            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ Request::is('admin/dashboard') || Request::is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-section-title">Manajemen Data</div>

            <div class="nav-item">
                <a href="{{ route('admin.laporan.index') }}"
                    class="nav-link {{ Request::is('admin/laporan*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span>Verifikasi Laporan</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.peta') }}" class="nav-link {{ Request::is('admin/peta') ? 'active' : '' }}">
                    <i class="fas fa-map-marked-alt"></i>
                    <span>Peta Monitoring</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('admin.points.index') }}"
                    class="nav-link {{ Request::is('admin/points*') ? 'active' : '' }}">
                    <i class="fas fa-map-pin"></i>
                    <span>Data Titik Terverifikasi</span>
                </a>
            </div>

            <div class="nav-section-title">Pengaturan</div>

            <div class="nav-item">
                <a href="{{ route('admin.users.index') }}"
                    class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i>
                    <span>Kelola Admin</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('profile.edit') }}"
                    class="nav-link {{ Request::is('profile*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </div>

            <div class="nav-section-title">Lainnya</div>

            <div class="nav-item">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-external-link-alt"></i>
                    <span>Lihat WebGIS Public</span>
                </a>
            </div>

            <div class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-100 border-0 bg-transparent" style="color: #ef4444;">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        @if (isset($header))
            <div class="mb-4">
                <div
                    style="background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05); border: 2px solid rgba(8, 145, 178, 0.1);">
                    {{ $header }}
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Toggle User Menu
        function toggleUserMenu() {
            const menu = document.querySelector('.user-menu');
            menu.classList.toggle('show');
        }

        // Toggle Sidebar (Mobile)
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.querySelector('.user-menu');
            if (userMenu && !userMenu.contains(event.target)) {
                userMenu.classList.remove('show');
            }
        });

        console.log('✅ Admin layout loaded with sidebar');
    </script>

    @stack('scripts')
    <!-- ⭐⭐⭐ NOTIFICATION BELL JAVASCRIPT ⭐⭐⭐ -->
    <script>
        let notificationBellInterval;

        function toggleNotifications() {
            const bell = document.querySelector('.notification-bell');
            bell.classList.toggle('show');

            if (bell.classList.contains('show')) {
                loadNotifications();
            }
        }

        function loadNotifications() {
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    const list = document.getElementById('notification-list');

                    if (data.notifications && data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(notif => `
                            <div class="notification-item ${!notif.is_read ? 'unread' : ''}"
                                 onclick="handleNotifClick(${notif.id}, ${notif.laporan_id})">
                                <div class="notification-title">${notif.title}</div>
                                <div class="notification-message">${notif.message}</div>
                                <div class="notification-time">${formatTimeAgo(notif.created_at)}</div>
                            </div>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="notification-item" style="text-align: center; color: #94a3b8;">Tidak ada notifikasi baru</div>';
                    }
                })
                .catch(err => {
                    console.error('Error loading notifications:', err);
                    document.getElementById('notification-list').innerHTML = '<div class="notification-item" style="text-align: center; color: #ef4444;">Gagal memuat notifikasi</div>';
                });
        }

        function updateNotifCount() {
            fetch('/api/notifications/count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notif-count');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(err => console.error('Error getting count:', err));
        }

        function handleNotifClick(notifId, laporanId) {
            fetch(`/api/notifications/${notifId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                window.location.href = '{{ route("admin.laporan.index") }}';
            });
        }

        function markAllRead() {
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(() => {
                updateNotifCount();
                loadNotifications();
            });
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) return 'Baru saja';
            if (seconds < 3600) return Math.floor(seconds / 60) + ' menit lalu';
            if (seconds < 86400) return Math.floor(seconds / 3600) + ' jam lalu';
            return Math.floor(seconds / 86400) + ' hari lalu';
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const bell = document.querySelector('.notification-bell');
            if (bell && !bell.contains(e.target)) {
                bell.classList.remove('show');
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔔 Initializing notification system...');
            updateNotifCount();
            notificationBellInterval = setInterval(updateNotifCount, 15000);
        });

        window.addEventListener('beforeunload', () => {
            if (notificationBellInterval) clearInterval(notificationBellInterval);
        });
    </script>
    <!-- ⭐⭐⭐ AUTO POPUP NOTIFICATION TOAST ⭐⭐⭐ -->
    @include('components.notification-toast')
</body>

</html>
