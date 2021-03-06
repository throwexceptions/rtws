@extends('layouts.app-map')

@section('content')
    <!--suppress ALL -->
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group" role="group" style="display: flex;">
                <button type="button" class="btn btn-primary" onclick="confirmLocation()">Confirm</button>
                <a href="{{ route('book') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
        <div class="col-md-12">
            <div id="map" style="width: 100vw; height: 95vh"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" defer>
        // init map
        var map = L.map('map', {
            zoomControl: false
        }).setView([14.434354283557754, 120.9925489160215], 6);

        // init map style
        var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 20,
        });

        // init zoom control
        L.control.zoom({
            position: 'topright'
        }).addTo(map);

        // init marker Group
        var markersById = {};
        var markerGroup = new L.layerGroup().addTo(map);

        // init events for map interaction
        function onMapClick(e) {
            markerGroup.clearLayers();
            marker = new L.marker(e.latlng);
            marker.addTo(markerGroup);
            reverseGeocoding(e.latlng)
            markersById[0] = marker;
        };

        function onSearch(latlng) {
            markerGroup.clearLayers();
            marker = new L.marker(latlng);
            marker.addTo(markerGroup);
        }

        map.on('click', onMapClick);

        map.addLayer(tiles);

        // init autocomplete Google maps searchables
        var g_name = '';
        var g_lat = '';
        var g_lng = '';
        new L.Control.GPlaceAutocomplete({
            callback: function (place) {
                g_name = place.formatted_address;

                var request = new XMLHttpRequest();
                request.open('GET', 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBQQkfIgi0W3SJX7KQddC6_k8L7ihvWaUI&address=' + g_name, false);
                request.send();

                var loc = $.parseJSON(request.response).results[0].geometry.location;
                console.log(loc);
                onSearch([loc.lat, loc.lng])

                map.panTo([loc.lat, loc.lng]);
                map.setZoom(15);
                g_lat = loc.lat;
                g_lng = loc.lng;
            },
            position: 'topleft',
        }).addTo(map);

        // init function confirm location
        function confirmLocation() {
            $.ajax({
                url: '{{ route('booking.location.store') }}',
                method: 'POST',
                data: {
                    name: g_name,
                    lat: g_lat,
                    lng: g_lng
                },
                success: function (value) {
                    window.location = '{{ route('book') }}';
                }
            });
        }

        function reverseGeocoding(latlng) {
            var geocoder = new google.maps.Geocoder();
            var address = 'London, UK';

            if (geocoder) {
                geocoder.geocode({'location': {lat: latlng.lat, lng: latlng.lng}},
                    function (results, status) {
                        g_lat = results[0].geometry.location.lat();
                        g_lng = results[0].geometry.location.lng()
                        g_name = results[0].formatted_address
                    });
            }
        }
    </script>

{{--    .setComponentRestrictions({--}}
{{--    country: ["us", "pr", "vi", "gu", "mp"],--}}
{{--    }--}}
@endsection
