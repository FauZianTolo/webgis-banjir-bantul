@extends('layouts.public')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        /* Hero Section */
        .route-hero {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            color: white;
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .route-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .route-hero h1 {
            font-weight: 900;
            font-size: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

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

        .loading-content {
            text-align: center;
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

        /* Leaflet Routing Machine Customization */
        .leaflet-routing-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 1rem;
        }

        .leaflet-routing-alt {
            background: #f0fdf4;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 0.5rem;
        }

        .leaflet-routing-alt:hover {
            background: #dcfce7;
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
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="route-hero">
        <div class="container">
            <h1 class="text-center mb-2">
                <i class="fas fa-route"></i> Navigasi Rute
            </h1>
            <p class="text-center lead opacity-90 mb-0">
                Rute dari Lokasi Anda ke {{ $targetTitle }}
            </p>
        </div>
    </div>

    <div class="container mt-4 mb-5">

        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('peta') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Peta
            </a>
        </div>

        <!-- Error Alert (Hidden by default) -->
        <div id="errorAlert" class="error-alert" style="display: none;">
            <i class="fas fa-exclamation-triangle fa-2x"></i>
            <div>
                <strong>Tidak dapat mengakses lokasi Anda</strong>
                <p class="mb-0 mt-1">Pastikan Anda mengizinkan akses lokasi di browser dan menggunakan HTTPS.</p>
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
                <div class="loading-content">
                    <div class="spinner-border-custom"></div>
                    <p class="text-muted mt-3">Menghitung rute terbaik...</p>
                </div>
            </div>

            <div id="routeMap"></div>
        </div>

    </div>
@endsection

@section('script')
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
            // Initialize map centered on target
            map = L.map('routeMap').setView([targetLat, targetLng], 13);

            // Add basemap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);

            // Add target marker
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

            // Create routing control
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
                }, // We already have custom markers
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    language: 'id',
                    profile: 'driving' // Options: driving, walking, cycling
                })
            }).addTo(map);

            // Listen for routing events
            routingControl.on('routesfound', function(e) {
                const routes = e.routes;
                const summary = routes[0].summary;

                // Update UI with route info
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

                // Hide loading
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

        // ⭐⭐⭐ GOOGLE MAPS NAVIGATION ⭐⭐⭐
        function openGoogleMaps() {
            const url = `https://www.google.com/maps/dir/?api=1&destination=${targetLat},${targetLng}&travelmode=driving`;
            window.open(url, '_blank');
        }

        console.log('✅ Route page loaded');
        console.log('📍 Target:', targetLat, targetLng);
    </script>
@endsection
