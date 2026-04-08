@extends('layouts.template')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
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
@endsection

<!-- Elemen untuk menampilkan peta -->
@section('content')
    <div id="map"></div>
@endsection


@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>
    <script src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        var coords = []; //define an array to store coordinates
        $.getJSON("{{ route('api.routing', $id) }}", function(data) {
            // point.addData(data);
            // map.addLayer(point);
            // map.fitBounds(point.getBounds());
            coords.push(data.features[0].geometry.coordinates[0]);
            coords.push(data.features[0].geometry.coordinates[1]);
            var lon = coords[0];
            console.log(lon);

            var map = L.map('map').setView([-6.2088, 106.8456], 13);
        // var destinationLatLng = L.latLng(-7.787824332432653,
        //     110.37365780937968);
        var destinationLatLng = L.latLng(coords[1],
            coords[0]);
        //Function to handle geolocation success
        function onLocationFound(e) {
            var userLatLng = e.latlng;

            // Define a destination LatLng (e.g., a specific location)
            //Replace with your destination coordinates

            // Add routing control to the map
            L.Routing.control({
                waypoints: [
                    userLatLng,
                    destinationLatLng
                ],
                routeWhileDragging: false
            }).addTo(map);

            // Set the map view to the user's location
            map.setView(routeWhileDragging, 13);
        }

        // Function to handle geolocation error
        function onLocationError(e) {
            alert(e.message);
        }

        // Request user's location
        map.on('locationfound', onLocationFound);
        map.on('locationerror', onLocationError);

        // Trigger the location request
        map.locate({
            setView: true,
            maxZoom: 10
        });



        // Menambahkan tile layer (misalnya dari OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);


            });

        // var latDes = coords[0][0];
        // console.log(latDes);
        // Membuat peta menggunakan Leaflet
        //GeoJSON Point
        // var point = L.geoJson(null, {
        //     onEachFeature: function(feature, layer) {
        //         coords.push([feature.geometry.coordinates[1],feature.geometry.coordinates[0]]);
        //         drawnItems.addLayer(layer);

        //         var popupContent = "Nama: " + feature.properties.name + "<br>" +
        //             "Deskripsi: " + feature.properties.description + "<br>" +
        //             "Foto: <br><img src='{{ asset('storage/images') }}/" + feature.properties.image +
        //             "' class='' alt='' width='200'>";

        //         layer.on({
        //             click: function(e) {
        //                 point.bindPopup(popupContent);
        //             },
        //             mouseover: function(e) {
        //                 point.bindTooltip(feature.properties.name);
        //             },
        //         });
        //     },
        // });
    </script>
@endsection
