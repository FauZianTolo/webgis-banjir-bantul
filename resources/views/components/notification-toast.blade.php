<div id="notification-container" style="position: fixed; top: 80px; right: 16px; z-index: 9999; width: 340px; max-width: calc(100vw - 24px);"></div>

<style>
    .toast-notification {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: start;
        gap: 1rem;
        animation: slideIn 0.5s ease;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .toast-notification:hover {
        transform: translateX(-5px);
    }

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

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        margin-bottom: 0.3rem;
        font-size: 0.95rem;
    }

    .toast-message {
        font-size: 0.85rem;
        color: #6b7280;
        line-height: 1.4;
    }

    .toast-time {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 0.3rem;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #9ca3af;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }

    .toast-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    /* Warna berdasarkan tipe */
    .toast-new_laporan {
        border-left-color: #ef4444;
    }

    .toast-new_laporan .toast-icon {
        background: #fef2f2;
        color: #ef4444;
    }

    .toast-verified {
        border-left-color: #10b981;
    }

    .toast-verified .toast-icon {
        background: #f0fdf4;
        color: #10b981;
    }

    .toast-rejected {
        border-left-color: #f59e0b;
    }

    .toast-rejected .toast-icon {
        background: #fffbeb;
        color: #f59e0b;
    }

    /* ── MOBILE ── */
    @media (max-width: 600px) {
        #notification-container {
            right: 8px !important;
            top: 76px !important;
            width: calc(100vw - 16px) !important;
            max-width: 100% !important;
        }
        .toast-notification {
            padding: 0.85rem;
            border-radius: 10px;
        }
        .toast-icon {
            width: 34px;
            height: 34px;
            font-size: 1rem;
        }
        .toast-title { font-size: 0.88rem; }
        .toast-message { font-size: 0.8rem; }
        .toast-time { font-size: 0.7rem; }
    }
</style>

<script>
    let notificationCheckInterval;
    let lastNotificationId = localStorage.getItem('lastNotificationId') || 0;
    let currentAudio = null; // ⭐ Variable untuk track audio yang sedang play

    // Start checking notifications
    function startNotificationChecker() {
        console.log('🔔 Starting notification checker...');
        checkNotifications();
        notificationCheckInterval = setInterval(checkNotifications, 10000); // Check every 10s
    }

    function checkNotifications() {
        fetch('/api/notifications/latest?after=' + lastNotificationId)
            .then(response => response.json())
            .then(data => {
                if (data.notifications && data.notifications.length > 0) {
                    console.log('🆕 Found', data.notifications.length, 'new notifications');
                    data.notifications.forEach(notification => {
                        showToast(notification);
                        lastNotificationId = Math.max(lastNotificationId, notification.id);
                        localStorage.setItem('lastNotificationId', lastNotificationId);
                    });
                }
            })
            .catch(error => console.error('❌ Error checking notifications:', error));
    }

    function showToast(notification) {
        console.log('📢 Showing toast:', notification.title);

        // ⭐⭐⭐ STOP AUDIO LAMA (jika ada yang masih play)
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }

        // ⭐⭐⭐ Play notification sound dari file MP3 lokal ⭐⭐⭐
        currentAudio = new Audio('/sounds/notification.mp3');
        currentAudio.volume = 0.8;
        currentAudio.play().catch(e => console.log('Audio play failed:', e));

        // Icon based on type
        let icon = '🔔';
        if (notification.type === 'new_laporan') icon = '🚨';
        if (notification.type === 'verified') icon = '✅';
        if (notification.type === 'rejected') icon = '❌';

        // Create toast
        const container = document.getElementById('notification-container');
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${notification.type}`;
        toast.innerHTML = `
        <div class="toast-icon">${icon}</div>
        <div class="toast-content">
            <div class="toast-title">${notification.title}</div>
            <div class="toast-message">${notification.message}</div>
            <div class="toast-time">Baru saja</div>
        </div>
        <button class="toast-close" onclick="closeToast(this, event)">×</button>
    `;

        // ⭐ Add click handler to redirect ke halaman laporan
        toast.style.cursor = 'pointer';
        toast.addEventListener('click', function(e) {
            // Jangan redirect kalau yang diklik tombol close
            if (!e.target.classList.contains('toast-close') && e.target.tagName !== 'BUTTON') {
                window.location.href = '{{ route('admin.laporan.index') }}';
            }
        });

        container.insertBefore(toast, container.firstChild);

        // ⭐⭐⭐ BERHENTI BARENGAN SETELAH 15 DETIK ⭐⭐⭐
        setTimeout(() => {
            if (toast.parentElement) {
                // Stop audio
                if (currentAudio) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0;
                    currentAudio = null;
                }

                // Hide toast
                toast.style.animation = 'slideOut 0.5s ease';
                setTimeout(() => toast.remove(), 500);
            }
        }, 15000); // ← 15 DETIK (notif + suara hilang barengan)

        // Mark as read after showing
        fetch(`/api/notifications/${notification.id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .catch(error => console.error('Error marking notification as read:', error));
    }

    function closeToast(button, event) {
        // ⭐ Prevent event bubbling (jangan trigger redirect)
        event.stopPropagation();

        const toast = button.closest('.toast-notification');

        // ⭐ Stop audio juga kalau user close manual
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }

        toast.style.animation = 'slideOut 0.5s ease';
        setTimeout(() => toast.remove(), 500);
    }

    // ⭐ Start HANYA jika user sudah login (admin)
    @auth
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
        // Stop audio juga
        if (currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
            currentAudio = null;
        }
    });
    @endauth
</script>
