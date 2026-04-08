@extends('layouts.template')

@section('styles')
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
        }

        #map {
            height: calc(100vh - 56px);
            width: 100%;
            margin: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection

<!-- Elemen untuk menampilkan peta -->
@section('content')
    <div id="map"></div>
@endsection

@section('script')
    <script src="https://unpkg.com/terraformer@1.0.7/terraformer.js"></script>
    <script src="https://unpkg.com/terraformer-wkt-parser@1.1.2/terraformer-wkt-parser.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        // Membuat peta menggunakan Leaflet
        var map = L.map('map').setView([-7.902918581392779, 110.35655776844843], 12);

        // Tile Basemap //
        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="FARAHSIG22" target="_blank">FARAHSIG22</a>' //menambahkan nama//
        });

        var basemap2 = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/ { z } / { y } / { x }', {
                attribution: 'Tiles &copy; Esri | <a href="Latihan WebGIS" target="_blank">FARAHSIG22</a>'
            });

        var basemap3 = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{ x }', {
                attribution: 'Tiles & copy; Esri | <a href="Lathan WebGIS" target="_blank">FARAHSIG22</a>'

            });

        var basemap4 = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org / ">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> FARAHSIG22'
        });

        basemap1.addTo(map);

        var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri World Street": basemap2,
            "Esri Imagery": basemap3,
            "Stadia Dark Mode": basemap4,
        };

        L.control.layers(baseMaps).addTo(map);

        /* Function to generate a random color */
        function getRandomColor() {
            const letters = '0123456789ABCDEF';
            let color = '#';
            for (let i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        /* GeoJSON Point */
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "'class='img-thumbnail' alt='...'>" + "<br>" +
                    "<div class='d-flex flex-row mt-2'>" +
                    "<a href='{{ url('routing-point') }}/" + feature.properties.id +
                    "' class='btn btn-info me-2'><i class='fa-solid fa-car-side'></i></a>" +
                    "</div>";

                layer.on({
                    click: function(e) {
                        point.bindPopup(popupContent);
                    },
                    mouseover: function(e) {
                        point.bindTooltip(feature.properties.name);
                    },
                });
            },
        });
        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        /* GeoJSON Polyline */
        function getStyle(feature) {
            switch (feature.properties.REMARK) {
                case 'jalan kolektor':
                    return {
                        color: "#B22222", weight: 3
                    };
                case 'jalan lain':
                    return {
                        color: "#B22222", weight: 2
                    };
                case 'jalan lokal':
                    return {
                        color: "#B22222", weight: 1
                    };
                case 'jalan setapak':
                    return {
                        color: "#B22222", weight: 0.5
                    };
                default:
                    return {
                        color: "#B22222", weight: 1
                    };
            }
        }

        // Membuat layer GeoJSON dengan style dan popup
        var polyline = L.geoJson(null, {
            style: getStyle,
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' class='img-thumbnail' alt='...'>"
                    ;
                layer.bindPopup(popupContent);
                layer.on({
                    mouseover: function(e) {
                        polyline.bindTooltip(feature.properties.name).openTooltip();
                    }
                });
            }
        });

        $.getJSON("{{ route('api.polylines') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        // Mengambil data GeoJSON dari URL dan menambahkannya ke peta
        fetch('{{ asset('storage/geojson/JalanYK.geojson') }}')
            .then(response => response.json())
            .then(data => {
                polyline.addData(data);
                map.addLayer(polyline);
            })
            .catch(error => console.log(error));



        //geojson polygon
        var userPolygons = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var popupContent = "Nama: " + feature.properties.name + "<br>" +
                    "Deskripsi: " + feature.properties.description + "<br>" +
                    "Foto: <img src='{{ asset('storage/images/') }}/" + feature.properties.image +
                    "' class='img-thumbnail' alt='...'>";
                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name, {
                            sticky: true
                        }).openTooltip();
                    },
                });
            },
        });

        // Load user polygons from API
        $.getJSON("{{ route('api.polygons') }}", function(data) {
            userPolygons.addData(data);
        });

        // GeoJSON layer for administrative boundaries
        var adminBoundaries = L.geoJson(null, {
            style: function(feature) {
                return {
                    opacity: 1,
                    color: "black",
                    weight: 0.5,
                    fillOpacity: 0.7,
                    fillColor: getRandomColor(),
                };
            },
            onEachFeature: function(feature, layer) {
                var content = "Kecamatan: " + feature.properties.WADMKC;
                layer.on({
                    click: function(e) {
                        layer.bindPopup(content).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindPopup("Kecamatan " + feature.properties.WADMKC, {
                            sticky: true
                        }).openPopup();
                    },
                    mouseout: function(e) {
                        layer.closePopup();
                    },
                });
            }
        });

        // Load administrative boundaries from GeoJSON file
        fetch('{{ asset('storage/geojson/bantul.geojson') }}')
            .then(response => response.json())
            .then(data => {
                adminBoundaries.addData(data);
            })
            .catch(error => {
                console.error('Error loading the GeoJSON file:', error);
            });


        var overlayLayers = {
            "User Polygons": userPolygons,
            "Administrative Boundaries": adminBoundaries
        };


        // Add layers to map
        userPolygons.addTo(map);
        adminBoundaries.addTo(map);

        // Layer control
        var overlayMaps = {
            "Point": point,
            "Polyline": polyline,
            "Polygon": userPolygons,
            "Admin Kecamatan": adminBoundaries
        };

        var layerControl = L.control.layers(null, overlayMaps, {}).addTo(map);

        // Menambahkan kontrol pencarian
        var searchControl = new L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            var bbox = e.geocode.bbox;
            var poly = L.polygon([
                bbox.getSouthEast(),
                bbox.getNorthEast(),
                bbox.getNorthWest(),
                bbox.getSouthWest()
            ]).addTo(map);
            map.fitBounds(poly.getBounds());
        }).addTo(map);
    </script>
@endsection
