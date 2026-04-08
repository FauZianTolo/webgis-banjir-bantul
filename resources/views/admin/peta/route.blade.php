<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 style="color: #0c4a6e; font-weight: 900; font-size: 2rem; margin: 0;">
                <i class="fas fa-route"></i> Navigasi Rute
            </h2>
            <p style="color: #64748b; margin: 0.5rem 0 0 0; font-weight: 600;">
                Rute dari Lokasi Anda ke {{ $targetTitle }}
            </p>
        </div>
    </x-slot>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        /* Map Container */
        #routeMap {
            height: 550px;
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border: 4px solid white;
        }

        .map-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(16, 185, 129, 0.15);
            margin-bottom: 2rem;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 2px solid rgba(16, 185, 129, 0.1);
            margin-bottom: 1.5rem;
        }

        .info-card h5 {
            color: #047857;
            font-weight: 800;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-card .info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f0fdf4;
            border-radius: 10px;
            margin-bottom: 0.5rem;
        }

        .info-card .info-label {
            color: #64748b;
            font-weight: 600;
        }

        .info-card .info-value {
            color: #0f172a;
            font-weight: 700;
        }

        /* Loading */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            border-radius: 20px;
        }

        .spinner-border-custom {
            width: 3rem;
            height: 3rem;
            border-width: 0.3rem;
            border-color: #10b981;
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Buttons */
        .btn-back {
            background: white;
            color: #059669;
            border: 2px solid #059669;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #059669;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3);
        }

        .btn-recenter {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-recenter:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-google-maps {
            background: linear-gradient(135deg, #4285f4, #34a853);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-google-maps:hover {
            background: linear-gradient(135deg, #3367d6, #2d9e47);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(66, 133, 244, 0.3);
        }

        /* Error Alert */
        .error-alert {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border-left: 5px solid #ef4444;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Custom Markers */
        .custom-marker-start {
            background: #10b981;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }

        .custom-marker-end {
            background: #ef4444;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
        }
    </style>
    @endpush

    <div class="container-fluid px-0 pb-5">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.peta') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Peta Monitoring
            </a>
        </div>

        <!-- Error Alert -->
        <div id="errorAlert" class="error-alert" style="display: none;">
            <i class="fas fa-exclamation-triangle fa-2x"></i>
            <div>
                <strong>Tidak dapat mengakses lokasi Anda</strong>
                <p class="mb-0 mt-1">Pastikan Anda mengizinkan akses lokasi di browser.</p>
            </div>
        </div>

        <!-- Route Info Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fas fa-map-marker-alt"></i> Tujuan</h5>
                    <div class="info-item">
                        <span class="info-label">Lokasi:</span>
                        <span class="info-value">{{ $targetTitle }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Koordinat:</span>
                        <span class="info-value">{{ $targetLat }}, {{ $targetLng }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fas fa-road"></i> Jarak & Waktu</h5>
                    <div class="info-item">
                        <span class="info-label">Jarak:</span>
                        <span class="info-value" id="routeDistance">Menghitung...</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Estimasi Waktu:</span>
                        <span class="info-value" id="routeDuration">Menghitung...</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-card">
                    <h5><i class="fas fa-location-arrow"></i> Navigasi</h5>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="info-value" id="locationStatus">Mencari lokasi...</span>
                    </div>
                    <div class="info-item"
                        style="flex-direction: column; align-items: stretch; gap: 0.5rem; padding: 0;">
                        <button class="btn-recenter" onclick="recenterMap()" style="width: 100%;">
                            <i class="fas fa-crosshairs"></i> Re-center Peta
                        </button>
                        <button class="btn-google-maps" onclick="openGoogleMaps()" style="width: 100%;">
                            <i class="fab fa-google"></i> Buka Google Maps
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Container -->
        <div class="map-wrapper" style="position: relative;">
            <!-- Loading Overlay -->
            <div id="loadingOverlay" class="loading-overlay">
                <div class="loading-content text-center">
                    <div class="spinner-border-custom"></div>
                    <p class="text-muted mt-3">Menghitung rute terbaik...</p>
                </div>
            </div>

            <div id="routeMap"></div>
        </div>

    </div>

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script>
        let map;
        let routingControl;
        let userMarker;
        let targetMarker;
        let userLocation = null;

        const targetLat = {{ $targetLat }};
        const targetLng = {{ $targetLng }};
        const targetTitle = "{{ $targetTitle }}";

        document.addEventListener('DOMContentLoaded', function() {
            initMap();
            getUserLocation();
        });

        function initMap() {
            map = L.map('routeMap').setView([targetLat, targetLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            const targetIcon = L.divIcon({
                className: 'custom-marker-end',
                html: '<i class="fas fa-flag-checkered"></i>',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            targetMarker = L.marker([targetLat, targetLng], {
                    icon: targetIcon
                })
                .addTo(map)
                .bindPopup(`<strong>${targetTitle}</strong><br>Tujuan Anda`);
        }

        function getUserLocation() {
            if (!navigator.geolocation) {
                showError('Browser Anda tidak mendukung Geolocation');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function(position) {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    document.getElementById('locationStatus').innerHTML =
                        '<span style="color: #10b981;"><i class="fas fa-check-circle"></i> Terdeteksi</span>';

                    addUserMarker();
                    calculateRoute();
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    showError('Tidak dapat mengakses lokasi. Error: ' + error.message);
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        function addUserMarker() {
            const userIcon = L.divIcon({
                className: 'custom-marker-start',
                html: '<i class="fas fa-user"></i>',
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            userMarker = L.marker([userLocation.lat, userLocation.lng], {
                    icon: userIcon
                })
                .addTo(map)
                .bindPopup('<strong>Posisi Anda</strong><br>Lokasi saat ini')
                .openPopup();
        }

        function calculateRoute() {
            if (!userLocation) {
                showError('Lokasi Anda belum terdeteksi');
                return;
            }

            routingControl = L.Routing.control({
                waypoints: [
                    L.latLng(userLocation.lat, userLocation.lng),
                    L.latLng(targetLat, targetLng)
                ],
                routeWhileDragging: false,
                addWaypoints: false,
                draggableWaypoints: false,
                fitSelectedRoutes: true,
                showAlternatives: true,
                lineOptions: {
                    styles: [{
                        color: '#10b981',
                        opacity: 0.8,
                        weight: 6
                    }]
                },
                altLineOptions: {
                    styles: [{
                        color: '#94a3b8',
                        opacity: 0.5,
                        weight: 4
                    }]
                },
                createMarker: function() {
                    return null;
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    language: 'id',
                    profile: 'driving'
                })
            }).addTo(map);

            routingControl.on('routesfound', function(e) {
                const routes = e.routes;
                const summary = routes[0].summary;

                const distanceKm = (summary.totalDistance / 1000).toFixed(2);
                const durationMin = Math.round(summary.totalTime / 60);
                const hours = Math.floor(durationMin / 60);
                const minutes = durationMin % 60;

                document.getElementById('routeDistance').textContent = distanceKm + ' km';

                if (hours > 0) {
                    document.getElementById('routeDuration').textContent = `${hours} jam ${minutes} menit`;
                } else {
                    document.getElementById('routeDuration').textContent = minutes + ' menit';
                }

                document.getElementById('loadingOverlay').style.display = 'none';
            });

            routingControl.on('routingerror', function(e) {
                console.error('Routing error:', e);
                showError('Tidak dapat menghitung rute. Coba lagi.');
                document.getElementById('loadingOverlay').style.display = 'none';
            });
        }

        function recenterMap() {
            if (userLocation && routingControl) {
                map.fitBounds([
                    [userLocation.lat, userLocation.lng],
                    [targetLat, targetLng]
                ], {
                    padding: [50, 50]
                });
            }
        }

        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            errorAlert.querySelector('p').textContent = message;
            errorAlert.style.display = 'flex';
            document.getElementById('loadingOverlay').style.display = 'none';
            document.getElementById('locationStatus').innerHTML =
                '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Gagal</span>';
        }

        function openGoogleMaps() {
            const url = `https://www.google.com/maps/dir/?api=1&destination=${targetLat},${targetLng}&travelmode=driving`;
            window.open(url, '_blank');
        }

        console.log('✅ Admin Route page loaded');
        console.log('📍 Target:', targetLat, targetLng);
    </script>
    @endpush
</x-app-layout>
