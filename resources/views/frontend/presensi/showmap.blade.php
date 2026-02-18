<style>
    #map {
        height: 500px;
        width: 100%;
        border-radius: 15px;
        border: 2px solid #f8f9fa;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .custom-popup .leaflet-popup-content-wrapper {
        background: #ffffff;
        color: #333;
        border-radius: 10px;
        padding: 5px;
    }
</style>

<div id="map"></div>



<script>
    // 1. Ambil data koordinat
    var locIn = "{{ $presence->location_in ?? '' }}";
    var locOut = "{{ $presence->location_out ?? '' }}";
    var officeCoords = [-6.216866477653331, 106.67630338286085];

    // Inisialisasi Map
    var map = L.map('map').setView(officeCoords, 17);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // 2. Definisi Ikon Kustom (Agar In dan Out beda warna)
    var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var redIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    var points = [];

    // --- PROSES LOKASI MASUK (IN) ---
    if (locIn != "") {
        var splitIn = locIn.split(",");
        var posIn = [parseFloat(splitIn[0]), parseFloat(splitIn[1])];

        L.marker(posIn, {
                icon: greenIcon
            }).addTo(map)
            .bindPopup("<b>MASUK (IN)</b><br>Jam: {{ date('H:i:s', strtotime($presence->time_in)) }}")
            .openPopup();

        points.push(posIn);
    }

    // --- PROSES LOKASI KELUAR (OUT) ---
    if (locOut != "" && locOut != null) {
        var splitOut = locOut.split(",");
        var posOut = [parseFloat(splitOut[0]), parseFloat(splitOut[1])];

        L.marker(posOut, {
                icon: redIcon
            }).addTo(map)
            .bindPopup("<b>KELUAR (OUT)</b><br>Jam: {{ date('H:i:s', strtotime($presence->time_out)) }}")
            .openPopup();
        points.push(posOut);
    }

    // --- RADIUS KANTOR ---
    L.circle(officeCoords, {
        color: 'blue',
        fillColor: '#30f',
        fillOpacity: 0.1,
        radius: 20
    }).addTo(map).bindPopup("Titik Kantor");

    // 3. ZOOM OTOMATIS
    if (points.length > 1) {
        // Jika ada Masuk DAN Keluar, zoom agar keduanya terlihat
        var bounds = new L.LatLngBounds(points);
        map.fitBounds(bounds, {
            padding: [50, 50]
        });
    } else if (points.length === 1) {
        // Jika cuma ada satu titik
        map.setView(points[0], 17);
    }

    // Perbaikan agar map tidak blank
    setTimeout(function() {
        map.invalidateSize();
    }, 500);
</script>

{{-- <script>
    var lokasi = "{{$presence->location_in}}";
    var lok = lokasi.split(",");
    var lat = lok[0];
    var log = lok[1];
    var map = L.map('map').setView([lat, log], 17);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([lat, log]).addTo(map);

    var circle = L.circle([-6.216866477653331, 106.67630338286085], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 20 //radius dengan satuan meter
    }).addTo(map);

    var popup = L.popup()
    .setLatLng([lat, log])
    .setContent("{{$presence->employee->first_name}}")
    .openOn(map);

</script> --}}
