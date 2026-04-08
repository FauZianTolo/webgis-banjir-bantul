<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                    <i class="fas fa-map-marked-alt"></i> Peta Monitoring - Semua Laporan
                </h2>
                <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                    Kelola dan verifikasi laporan banjir dari masyarakat
                </p>
            </div>
        </div>
    </x-slot>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    @push('styles')
        <style>
            /* ==================== MAP CONTAINER ==================== */
            .map-admin-container {
                height: calc(100vh - 200px);
                width: 100%;
                border-radius: 20px;
                box-shadow: 0 15px 50px rgba(8, 145, 178, 0.2);
                border: 4px solid white;
            }

            /* ==================== INFO BOX (STATS) ==================== */
            .info-box {
                background: white;
                padding: 2rem;
                margin-bottom: 2rem;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                border: 2px solid rgba(8, 145, 178, 0.1);
            }

            .info-box h4 {
                color: #0c4a6e;
                font-weight: 900;
                font-size: 1.5rem;
                margin-bottom: 1rem;
            }

            .info-box p {
                color: #64748b;
                font-weight: 600;
                font-size: 1.05rem;
                margin: 0;
            }

            /* ==================== STATUS INDICATORS ==================== */
            .status-indicator {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                border-radius: 10px;
                font-weight: 700;
                margin-right: 1rem;
                transition: all 0.3s ease;
            }

            .status-indicator:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            }

            .status-indicator i {
                font-size: 0.9rem;
            }

            .status-pending {
                background: linear-gradient(135deg, #fef3c7, #fde68a);
                color: #92400e;
                border: 2px solid #f59e0b;
            }

            .status-pending i {
                color: #f59e0b;
            }

            .status-verified {
                background: linear-gradient(135deg, #d1fae5, #a7f3d0);
                color: #065f46;
                border: 2px solid #10b981;
            }

            .status-verified i {
                color: #10b981;
            }

            .status-rejected {
                background: linear-gradient(135deg, #fee2e2, #fecaca);
                color: #991b1b;
                border: 2px solid #ef4444;
            }

            .status-rejected i {
                color: #ef4444;
            }

            /* ==================== BUTTON ==================== */
            .btn-kelola {
                background: linear-gradient(135deg, #0891b2, #06b6d4);
                color: white;
                border: none;
                padding: 0.75rem 1.5rem;
                border-radius: 12px;
                font-weight: 700;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
            }

            .btn-kelola:hover {
                background: linear-gradient(135deg, #0e7490, #0891b2);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
                color: white;
            }

            /* ==================== DETAIL MODAL ==================== */
            .detail-modal {
                display: none;
                position: fixed;
                z-index: 99998;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(5px);
                animation: fadeIn 0.3s ease;
            }

            .detail-modal.show {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .detail-modal-content {
                background: white;
                border-radius: 25px;
                width: 90%;
                max-width: 900px;
                max-height: 85vh;
                overflow-y: auto;
                box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
                position: relative;
                animation: slideUp 0.3s ease;
            }

            .detail-modal-header {
                background: linear-gradient(135deg, #0891b2, #06b6d4);
                color: white;
                padding: 2rem;
                border-radius: 25px 25px 0 0;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .detail-modal-header h3 {
                margin: 0;
                font-size: 1.75rem;
                font-weight: 900;
            }

            .detail-modal-close {
                position: absolute;
                top: 1.5rem;
                right: 1.5rem;
                background: rgba(255, 255, 255, 0.2);
                border: 2px solid rgba(255, 255, 255, 0.5);
                color: white;
                width: 45px;
                height: 45px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1.5rem;
                font-weight: bold;
                transition: all 0.3s ease;
            }

            .detail-modal-close:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(90deg);
            }

            .detail-modal-body {
                padding: 2.5rem;
            }

            .detail-section {
                margin-bottom: 2rem;
            }

            .detail-section-title {
                color: #0c4a6e;
                font-weight: 800;
                font-size: 1.25rem;
                margin-bottom: 1.25rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding-bottom: 0.75rem;
                border-bottom: 3px solid #e2e8f0;
            }

            .detail-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
            }

            .detail-item {
                background: #f8fafc;
                border-radius: 12px;
                padding: 1.25rem;
                border: 2px solid #e2e8f0;
            }

            .detail-item-label {
                color: #64748b;
                font-size: 0.85rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-bottom: 0.5rem;
            }

            .detail-item-value {
                color: #0f172a;
                font-size: 1.1rem;
                font-weight: 700;
            }

            .detail-foto {
                width: 100%;
                max-width: 500px;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .detail-foto:hover {
                transform: scale(1.05);
                box-shadow: 0 15px 50px rgba(8, 145, 178, 0.3);
            }

            .badge-status {
                padding: 0.5rem 1rem;
                border-radius: 10px;
                font-weight: 700;
                font-size: 0.85rem;
                display: inline-block;
            }

            .badge-pending {
                background: linear-gradient(135deg, #fef3c7, #fde68a);
                color: #92400e;
                border: 2px solid #f59e0b;
            }

            .badge-verified {
                background: linear-gradient(135deg, #d1fae5, #a7f3d0);
                color: #065f46;
                border: 2px solid #10b981;
            }

            .badge-rejected {
                background: linear-gradient(135deg, #fee2e2, #fecaca);
                color: #991b1b;
                border: 2px solid #ef4444;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
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

            /* ==================== POPUP BUTTON ==================== */
            .popup-btn-detail {
                background: linear-gradient(135deg, #0891b2, #06b6d4);
                color: white;
                border: none;
                padding: 0.6rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .popup-btn-detail:hover {
                background: linear-gradient(135deg, #0e7490, #0891b2);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(8, 145, 178, 0.3);
            }

            /* ==================== POPUP ROUTE BUTTON ==================== */
            .popup-btn-route {
                background: linear-gradient(135deg, #10b981, #059669);
                color: white;
                border: none;
                padding: 0.6rem 1rem;
                border-radius: 8px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .popup-btn-route:hover {
                background: linear-gradient(135deg, #059669, #047857);
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
            }

            /* Modal Styling */
            .modal-image-viewer {
                display: none;
                position: fixed;
                z-index: 99999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.9);
                backdrop-filter: blur(4px);
            }

            .modal-image-content {
                position: relative;
                margin: auto;
                padding: 0;
                width: 90%;
                max-width: 1200px;
                top: 50%;
                transform: translateY(-50%);
            }

            .modal-image-viewer img {
                width: 100%;
                height: auto;
                max-height: 85vh;
                object-fit: contain;
                border-radius: 8px;
            }

            .modal-close-btn {
                position: absolute;
                top: -15px;
                right: -15px;
                color: #fff;
                background: #ef4444;
                font-size: 32px;
                font-weight: bold;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                border: 3px solid white;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s;
                z-index: 100000;
            }

            .modal-close-btn:hover {
                background: #dc2626;
                transform: scale(1.1);
            }
        </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        <div class="info-box">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-3">
                        <i class="fas fa-layer-group"></i> Status Laporan
                    </h4>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="status-indicator status-pending">
                            <i class="fas fa-circle"></i>
                            Pending: {{ $laporan->where('status', 'pending')->count() }}
                        </span>
                        <span class="status-indicator status-verified">
                            <i class="fas fa-circle"></i>
                            Verified: {{ $laporan->where('status', 'verified')->count() }}
                        </span>
                        <span class="status-indicator status-rejected">
                            <i class="fas fa-circle"></i>
                            Rejected: {{ $laporan->where('status', 'rejected')->count() }}
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.laporan.index') }}" class="btn-kelola">
                        <i class="fas fa-clipboard-check"></i> Kelola Laporan
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-xl rounded-3" style="border-radius: 20px; padding: 1rem;">
            <div class="map-admin-container" id="map"></div>
        </div>

    </div>

    <!-- Detail Modal -->
    <div id="detailModal" class="detail-modal" onclick="closeDetailModal()">
        <div class="detail-modal-content" onclick="event.stopPropagation()">
            <div class="detail-modal-header">
                <h3><i class="fas fa-file-alt"></i> Detail Laporan Banjir</h3>
                <button class="detail-modal-close" onclick="closeDetailModal()">×</button>
            </div>

            <div class="detail-modal-body">
                <!-- Informasi Laporan -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-info-circle"></i> Informasi Laporan
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">ID Laporan</div>
                            <div class="detail-item-value" id="detail-id">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Tanggal & Waktu</div>
                            <div class="detail-item-value" id="detail-waktu">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Status</div>
                            <div class="detail-item-value" id="detail-status">-</div>
                        </div>
                    </div>
                </div>

                <!-- Data Pelapor -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-user"></i> Data Pelapor
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Nama Pelapor</div>
                            <div class="detail-item-value" id="detail-pelapor">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">No. Telepon</div>
                            <div class="detail-item-value" id="detail-telp">-</div>
                        </div>
                    </div>
                </div>

                <!-- Lokasi Banjir -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-map-marker-alt"></i> Lokasi Banjir
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Kecamatan</div>
                            <div class="detail-item-value" id="detail-kecamatan">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Desa</div>
                            <div class="detail-item-value" id="detail-desa">-</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-item-label">Koordinat</div>
                            <div class="detail-item-value" id="detail-koordinat">-</div>
                        </div>
                    </div>
                </div>

                <!-- Kondisi Banjir -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-water"></i> Kondisi Banjir
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-item-label">Kedalaman Air</div>
                            <div class="detail-item-value" id="detail-kedalaman">-</div>
                        </div>
                    </div>
                    <div class="detail-item mt-3">
                        <div class="detail-item-label">Deskripsi</div>
                        <div class="detail-item-value" id="detail-deskripsi"
                            style="font-weight: 500; line-height: 1.6;">-</div>
                    </div>
                </div>

                <!-- Foto Laporan -->
                <div class="detail-section">
                    <h4 class="detail-section-title">
                        <i class="fas fa-camera"></i> Dokumentasi Foto
                    </h4>
                    <div id="detail-foto-container" style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                        <!-- Diisi oleh JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Image Viewer -->
    <div id="imageModal" class="modal-image-viewer">
        <div class="modal-image-content">
            <span class="modal-close-btn" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" src="" alt="Foto Laporan">
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            // ⭐⭐⭐ DATA LAPORAN ⭐⭐⭐
            const laporanData = @json($laporan->keyBy('id'));

            // ⭐⭐⭐ MODAL IMAGE FUNCTIONS ⭐⭐⭐
            function openImageModal(src) {
                event.stopPropagation();
                event.preventDefault();
                document.getElementById('modalImage').src = src;
                document.getElementById('imageModal').style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closeImageModal() {
                document.getElementById('imageModal').style.display = 'none';
                document.body.style.overflow = '';
            }

            // ⭐⭐⭐ DETAIL MODAL FUNCTIONS ⭐⭐⭐
            function showDetail(laporanId) {
                const laporan = laporanData[laporanId];

                if (!laporan) {
                    alert('Data laporan tidak ditemukan');
                    return;
                }

                // Populate modal
                document.getElementById('detail-id').textContent = laporan.id;
                document.getElementById('detail-waktu').textContent = formatDateTime(laporan.waktu_laporan);

                // Status badge
                let statusBadge = '';
                if (laporan.status === 'pending') {
                    statusBadge = '<span class="badge-status badge-pending"><i class="fas fa-clock"></i> Pending</span>';
                } else if (laporan.status === 'verified') {
                    statusBadge =
                        '<span class="badge-status badge-verified"><i class="fas fa-check-circle"></i> Verified</span>';
                } else {
                    statusBadge =
                        '<span class="badge-status badge-rejected"><i class="fas fa-times-circle"></i> Rejected</span>';
                }
                document.getElementById('detail-status').innerHTML = statusBadge;

                document.getElementById('detail-pelapor').textContent = laporan.nama_pelapor || '-';
                document.getElementById('detail-telp').textContent = laporan.no_telp || '-';
                document.getElementById('detail-kecamatan').textContent = laporan.kecamatan || '-';
                document.getElementById('detail-desa').textContent = laporan.desa || '-';
                document.getElementById('detail-koordinat').textContent = `${laporan.latitude}, ${laporan.longitude}`;
                document.getElementById('detail-kedalaman').textContent = `${laporan.kedalaman_cm || 0} cm`;
                document.getElementById('detail-deskripsi').textContent = laporan.deskripsi || 'Tidak ada deskripsi';

                // Foto
                const fotoContainer = document.getElementById('detail-foto-container');
                const fotoFields = [laporan.foto, laporan.foto2, laporan.foto3].filter(Boolean);

                if (fotoFields.length > 0) {
                    fotoContainer.innerHTML = fotoFields.map((f, i) => `
                        <div style="text-align:center;">
                            <img src="/uploads/laporan/${f}"
                                 alt="Foto ${i+1}"
                                 class="detail-foto"
                                 onclick="openImageModal('/uploads/laporan/${f}')"
                                 style="width:155px;height:155px;object-fit:cover;border-radius:14px;cursor:pointer;border:3px solid #e2e8f0;box-shadow:0 6px 20px rgba(0,0,0,0.1);">
                            <div style="font-size:11px;font-weight:700;color:#64748b;margin-top:5px;">Foto ${i+1}</div>
                        </div>
                    `).join('');
                } else {
                    fotoContainer.innerHTML =
                        '<p class="text-muted" style="width:100%;text-align:center;padding:1.5rem 0;"><i class="fas fa-image fa-3x mb-3 d-block"></i>Tidak ada foto</p>';
                }

                // Show modal
                document.getElementById('detailModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeDetailModal() {
                document.getElementById('detailModal').classList.remove('show');
                document.body.style.overflow = '';
            }

            function formatDateTime(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            // ⭐⭐⭐ ROUTING FUNCTIONS ⭐⭐⭐
            function goToRoute(lat, lng, title) {
                const encodedTitle = encodeURIComponent(title);
                window.location.href = `/admin/peta/rute?lat=${lat}&lng=${lng}&title=${encodedTitle}`;
            }

            // Close on ESC key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                    closeDetailModal();
                }
            });

            // Close on background click
            document.getElementById('imageModal').addEventListener('click', function(e) {
                if (e.target === this) closeImageModal();
            });

            // ⭐⭐⭐ MAP INITIALIZATION ⭐⭐⭐
            document.addEventListener('DOMContentLoaded', function() {
                var map = L.map('map').setView([-7.8700, 110.3300], 11);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    maxZoom: 18,
                }).addTo(map);

                // Load zona kerawanan
                fetch('/storage/geojson/bantul_dummy.geojson')
                    .then(response => response.json())
                    .then(data => {
                        L.geoJSON(data, {
                            style: function(feature) {
                                var fillColor = feature.properties.ZONA === 'Tinggi' ? '#ff0000' :
                                    feature.properties.ZONA === 'Sedang' ? '#ffff00' : '#00ff00';
                                return {
                                    fillColor: fillColor,
                                    fillOpacity: 0.3,
                                    color: '#000',
                                    weight: 1
                                };
                            }
                        }).addTo(map);
                    });

                // ⭐⭐⭐ LOAD BATAS ADMINISTRASI ⭐⭐⭐
                fetch('/storage/geojson/bantul.geojson')
                    .then(response => response.json())
                    .then(data => {
                        L.geoJSON(data, {
                            style: {
                                fillColor: 'transparent',
                                color: '#1e3c72',
                                weight: 2,
                                opacity: 0.8,
                                dashArray: '5, 5'
                            },
                            onEachFeature: function(feature, layer) {
                                const props = feature.properties;
                                layer.bindPopup(`
                    <div style="min-width: 150px;">
                        <h6><strong>Batas Administrasi</strong></h6>
                        <p class="mb-0">Kecamatan ${props.WADMC || props.WADMKC || 'Kab. Bantul'}</p>
                    </div>
                `);
                            }
                        }).addTo(map);
                    })
                    .catch(err => console.error('Error loading batas admin:', err));

                // Icon berdasarkan status
                var pendingIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-yellow.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });

                var verifiedIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });

                var rejectedIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34]
                });

                // Add markers dengan FOTO + DETAIL & RUTE BUTTON
                const laporanArray = Object.values(laporanData);

                laporanArray.forEach(function(item) {
                    var icon = item.status === 'pending' ? pendingIcon :
                        item.status === 'verified' ? verifiedIcon : rejectedIcon;

                    var statusColor = item.status === 'pending' ? '#fef3c7' :
                        item.status === 'verified' ? '#d1fae5' : '#fee2e2';
                    var statusTextColor = item.status === 'pending' ? '#92400e' :
                        item.status === 'verified' ? '#065f46' : '#991b1b';
                    var statusText = item.status === 'pending' ? 'PENDING' :
                        item.status === 'verified' ? 'VERIFIED' : 'REJECTED';

                    var marker = L.marker([item.latitude, item.longitude], {
                        icon: icon
                    }).addTo(map);

                    // BUILD POPUP
                    var popupContent = '<div style="min-width: 260px; max-width: 320px;">' +
                        '<h6 style="font-weight: bold; margin-bottom: 10px; font-size: 14px;">Laporan #' + item
                        .id + '</h6>';

                    // MULTI FOTO (max 3)
                    var popupFotos = [item.foto, item.foto2, item.foto3].filter(Boolean);
                    if (popupFotos.length > 0) {
                        popupContent += '<div style="display:flex;gap:4px;margin-bottom:10px;">';
                        popupFotos.forEach(function(f, i) {
                            var w = popupFotos.length === 1 ? '100%' : (popupFotos.length === 2 ? 'calc(50% - 2px)' : 'calc(33.3% - 3px)');
                            popupContent += '<img src="/uploads/laporan/' + f + '" ' +
                                'alt="Foto ' + (i+1) + '" ' +
                                'style="width:' + w + ';height:75px;object-fit:cover;border-radius:7px;cursor:pointer;border:2px solid #e2e8f0;" ' +
                                'onclick="openImageModal(\'/uploads/laporan/' + f + '\')" ' +
                                'title="Foto ' + (i+1) + ' — klik untuk memperbesar">';
                        });
                        popupContent += '</div>';
                        if (popupFotos.length > 1) {
                            popupContent += '<p style="font-size:10px;color:#64748b;margin-bottom:6px;text-align:center;">' + popupFotos.length + ' foto tersedia</p>';
                        }
                    }

                    popupContent += '<p style="margin: 6px 0;"><strong>Status:</strong> ' +
                        '<span style="background: ' + statusColor + '; color: ' + statusTextColor +
                        '; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">' +
                        statusText +
                        '</span></p>' +
                        '<p style="margin: 6px 0;"><strong>Lokasi:</strong> ' + item.kecamatan + ', ' + item
                        .desa + '</p>' +
                        '<p style="margin: 6px 0;"><strong>Kedalaman:</strong> <span style="font-weight: bold; color: ' +
                        (item.kedalaman_cm >= 70 ? '#991b1b' : item.kedalaman_cm >= 40 ? '#92400e' :
                        '#1e40af') + ';">' +
                        item.kedalaman_cm + ' cm</span></p>' +
                        '<p style="margin: 6px 0;"><strong>Pelapor:</strong> ' + item.nama_pelapor + '</p>' +
                        '<p style="margin: 6px 0 12px 0;"><strong>Waktu:</strong> ' + new Date(item
                            .waktu_laporan).toLocaleString('id-ID') + '</p>' +

                        // TOMBOL DETAIL & RUTE
                        '<div style="display: flex; gap: 8px;">' +
                        '<button class="popup-btn-detail" onclick="showDetail(' + item.id +
                        ')" style="flex: 1;">' +
                        '<i class="fas fa-info-circle"></i> Detail' +
                        '</button>' +
                        '<button class="popup-btn-route" onclick="goToRoute(' + item.latitude + ', ' + item
                        .longitude + ', \'Laporan #' + item.id + '\')" style="flex: 1;">' +
                        '<i class="fas fa-route"></i> Rute' +
                        '</button>' +
                        '</div>' +
                        '</div>';

                    marker.bindPopup(popupContent, {
                        maxWidth: 340
                    });
                });

                console.log('✅ Admin Peta loaded with detail modal & routing');
                console.log('📊 Total laporan:', laporanArray.length);
            });
        </script>
    @endpush
</x-app-layout>
