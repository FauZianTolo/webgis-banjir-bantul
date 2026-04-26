<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />{{ __('Home') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                        {{ __('Verifikasi Laporan') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.peta')" :active="request()->routeIs('admin.peta')">
                        {{ __('Peta Monitoring') }}
                    </x-nav-link>

                    <!-- ⭐ TAMBAHKAN INI -->
                    <x-nav-link :href="route('admin.points.index')" :active="request()->routeIs('admin.points.*')">
                        {{ __('Data Points') }}
                    </x-nav-link>

                    <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Kelola Admin') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <!-- ⭐ BELL ICON NOTIFIKASI -->
                <div class="relative me-3" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>

                        <!-- Badge -->
                        <span id="notif-badge"
                            class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"
                            style="display: none;">
                            0
                        </span>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                        style="display: none;">

                        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800">Notifikasi</h3>
                            <button onclick="markAllRead()" class="text-sm text-blue-600 hover:text-blue-800">
                                Tandai Semua Dibaca
                            </button>
                        </div>

                        <div id="notif-list" class="max-h-96 overflow-y-auto">
                            <div class="p-4 text-center text-gray-500">
                                <div class="spinner-border spinner-border-sm"></div>
                                <p class="mt-2 text-sm">Memuat notifikasi...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown (Settings) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs('admin.laporan.*')">
                {{ __('Verifikasi Laporan') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.peta')" :active="request()->routeIs('admin.peta')">
                {{ __('Peta Monitoring') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                {{ __('Kelola Admin') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- ⭐⭐⭐ TOAST POPUP CONTAINER (Pojok Kanan Atas) ⭐⭐⭐ -->
@auth
    <div id="toast-container" style="position: fixed; top: 80px; right: 20px; z-index: 9999; max-width: 380px;"></div>

    <!-- ⭐⭐⭐ AUDIO NOTIFIKASI ⭐⭐⭐ -->
    <audio id="notification-sound" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3" type="audio/mpeg">
    </audio>
@endauth

<!-- ⭐⭐⭐ NOTIFICATION SCRIPTS (REAL-TIME) ⭐⭐⭐ -->
@auth
    <script>
        let lastNotificationId = parseInt(localStorage.getItem('lastNotificationId') || '0');
        let notificationCheckInterval;

        // ========== TOAST POPUP FUNCTIONS ==========
        function showToast(notification) {
            console.log('Showing toast for notification:', notification);

            // Play sound
            const sound = document.getElementById('notification-sound');
            if (sound) {
                sound.volume = 0.5;
                sound.play().catch(e => console.log('Sound play failed:', e));
            }

            // Icon based on type
            let icon = '🔔';
            let borderColor = '#3b82f6';
            let bgColor = '#eff6ff';

            if (notification.type === 'new_laporan') {
                icon = '🚨';
                borderColor = '#ef4444';
                bgColor = '#fef2f2';
            } else if (notification.type === 'verified') {
                icon = '✅';
                borderColor = '#10b981';
                bgColor = '#f0fdf4';
            } else if (notification.type === 'rejected') {
                icon = '❌';
                borderColor = '#f59e0b';
                bgColor = '#fffbeb';
            }

            // Create toast element
            const container = document.getElementById('toast-container');
            if (!container) {
                console.error('Toast container not found!');
                return;
            }

            const toast = document.createElement('div');
            toast.className = 'toast-notification mb-3';
            toast.style.cssText = `
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        padding: 1rem;
        display: flex;
        align-items: start;
        gap: 1rem;
        animation: slideIn 0.5s ease;
        border-left: 4px solid ${borderColor};
        position: relative;
    `;

            toast.innerHTML = `
        <div style="width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; background: ${bgColor};">
            ${icon}
        </div>
        <div style="flex: 1; min-width: 0;">
            <div style="font-weight: 700; margin-bottom: 0.3rem; font-size: 0.95rem; color: #1f2937;">
                ${notification.title}
            </div>
            <div style="font-size: 0.85rem; color: #6b7280; line-height: 1.4; word-wrap: break-word;">
                ${notification.message}
            </div>
            <div style="font-size: 0.75rem; color: #9ca3af; margin-top: 0.3rem;">
                Baru saja
            </div>
        </div>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; padding: 0; width: 24px; height: 24px; line-height: 1; flex-shrink: 0;">
            ×
        </button>
    `;

            container.insertBefore(toast, container.firstChild);

            // Auto remove after 8 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOut 0.5s ease';
                    setTimeout(() => toast.remove(), 500);
                }
            }, 8000);

            // Mark as read after showing (delayed)
            setTimeout(() => {
                fetch(`/api/notifications/${notification.id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).catch(err => console.error('Error marking as read:', err));
            }, 2000);
        }

        // ========== CHECK NEW NOTIFICATIONS (REAL-TIME) ==========
        function checkNewNotifications() {
            fetch('/api/notifications/latest?after=' + lastNotificationId)
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok');
                    return r.json();
                })
                .then(data => {
                    console.log('Checking notifications, response:', data);

                    if (data.success && data.notifications && data.notifications.length > 0) {
                        console.log('Found new notifications:', data.notifications.length);

                        data.notifications.forEach(notification => {
                            showToast(notification);
                            lastNotificationId = Math.max(lastNotificationId, notification.id);
                            localStorage.setItem('lastNotificationId', lastNotificationId); // ✅ persist antar halaman

                            // Refresh dashboard stats jika di halaman dashboard
                            if (window.location.pathname === '/dashboard') {
                                refreshDashboardStats();
                            }
                        });

                        // Update badge & dropdown list
                        updateBellBadge();
                        loadNotifications();
                    }
                })
                .catch(error => console.error('Error checking notifications:', error));
        }

        // ========== BELL BADGE & DROPDOWN ==========
        function updateBellBadge() {
            fetch('/api/notifications/count')
                .then(r => r.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    if (badge) {
                        if (data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-flex';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.error('Error updating badge:', err));
        }

        function loadNotifications() {
            fetch('/api/notifications')
                .then(r => r.json())
                .then(data => {
                    const list = document.getElementById('notif-list');
                    if (!list) return;

                    if (!data.notifications || data.notifications.length === 0) {
                        list.innerHTML =
                        '<div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>';
                        return;
                    }

                    list.innerHTML = data.notifications.map(n => {
                        const icon = n.type === 'new_laporan' ? '🚨' : n.type === 'verified' ? '✅' : '❌';
                        const bgClass = !n.is_read ? 'bg-blue-50' : '';

                        return `
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 cursor-pointer ${bgClass}"
                         onclick="markAsRead(${n.id})">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-xl">
                                ${icon}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm text-gray-800">${n.title}</h4>
                                <p class="text-xs text-gray-600 mt-1 break-words">${n.message}</p>
                                <span class="text-xs text-gray-400 mt-1">${new Date(n.created_at).toLocaleString('id-ID')}</span>
                            </div>
                            ${!n.is_read ? '<span class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0"></span>' : ''}
                        </div>
                    </div>
                `;
                    }).join('');
                })
                .catch(err => console.error('Error loading notifications:', err));
        }

        function markAsRead(id) {
            fetch(`/api/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(() => {
                    loadNotifications();
                    updateBellBadge();
                })
                .catch(err => console.error('Error marking as read:', err));
        }

        function markAllRead() {
            fetch('/api/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(() => {
                    loadNotifications();
                    updateBellBadge();
                })
                .catch(err => console.error('Error marking all as read:', err));
        }

        // ========== REFRESH DASHBOARD STATS ==========
        function refreshDashboardStats() {
            fetch('/api/dashboard/refresh')
                .then(r => r.json())
                .then(data => {
                    console.log('Refreshing dashboard stats:', data);

                    // Update stats di dashboard
                    const pendingEl = document.querySelector('[data-stat="pending"]');
                    const verifiedEl = document.querySelector('[data-stat="verified"]');
                    const totalEl = document.querySelector('[data-stat="total"]');

                    if (pendingEl) pendingEl.textContent = data.laporanPending || 0;
                    if (verifiedEl) verifiedEl.textContent = data.laporanVerified || 0;
                    if (totalEl) totalEl.textContent = data.totalLaporan || 0;
                })
                .catch(err => console.error('Error refreshing stats:', err));
        }

        // ========== START NOTIFICATION CHECKER ==========
        function startNotificationChecker() {
            console.log('Starting notification checker...');

            // Check immediately
            updateBellBadge();
            loadNotifications();

            // Wait 3 seconds before first check for new notifications
            setTimeout(() => {
                checkNewNotifications();

                // ✅ OPTIMASI: 30 detik + skip saat tab tidak aktif
                notificationCheckInterval = setInterval(() => {
                    if (document.hidden) return;
                    checkNewNotifications();
                }, 30000);
            }, 3000);
        }

        // ✅ OPTIMASI: Resume cek notifikasi saat user kembali ke tab
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) checkNewNotifications();
        });

        // ========== AUTO START ==========
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startNotificationChecker);
        } else {
            startNotificationChecker();
        }

        // Stop when page unloads
        window.addEventListener('beforeunload', () => {
            if (notificationCheckInterval) {
                clearInterval(notificationCheckInterval);
            }
        });

        // ========== CSS ANIMATIONS ==========
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    .spinner-border {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        vertical-align: text-bottom;
        border: 0.25em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: spinner-border 0.75s linear infinite;
    }
    @keyframes spinner-border {
        to { transform: rotate(360deg); }
    }
`;
        document.head.appendChild(style);
    </script>
@endauth
