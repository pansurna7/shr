
<style>
    #map {
            height: 500px;

            }
</style>

<div id="map"></div>

<script>
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

</script>

