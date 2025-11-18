@extends('frontend.layout.app')
@section('title','Attendence')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/frontend/dashboards" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle text-uppercase">@yield('title')</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
    .webcam-capture,
    .webcam-capture video{
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
    #map {
        height: 300px;
        }
</style>
@endsection

@section('content')

    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="show" id="lokasi">
            <div class="webcam-capture"></div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button id="takeAbsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>Absen Pulang
                </button>
            @else
                <button id="takeAbsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>Absen Masuk
                </button>
            @endif

        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
    <audio id="notif_in">
        <source src="{{ asset('assets/frontend/assets/sound/notif_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_out">
        <source src="{{ asset('assets/frontend/assets/sound/notif_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_error">
        <source src="{{ asset('assets/frontend/assets/sound/notif_error.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notif_error_radius">
        <source src="{{ asset('assets/frontend/assets/sound/notif_error_radius.mp3') }}" type="audio/mpeg">
    </audio>
@endsection
@push('scripts')
    <script>
        Webcam.set({
            height: 480,
            width:640,
            image_format:"jpeg",
            jpeg_quality:80,
        });
        Webcam.attach('.webcam-capture');

        var lokasi = document.getElementById('lokasi');
        if(navigator.geolocation){
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

        }

        function successCallback(position)
        {
            lokasi.value = position.coords.latitude+","+position.coords.longitude;

            // menggunakan JS Leaflet
            var map             = L.map('map').setView([position.coords.latitude, position.coords.longitude], 17);
            var office_location = "{{$office_location->location}}"
            var radius          = "{{$office_location->radius}}"
            var lok             = office_location.split(",");
            var office_lat      = lok[0];
            var office_log      = lok[1];

            L.tileLayer('http://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}',{
                maxZoom: 20,
                subdomains:['mt0','mt1','mt2','mt3']
            }).addTo(map);

            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([office_lat, office_log], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius //radius dengan satuan meter
            }).addTo(map);
        }

        function errorCallback()
        {

        }

        $("#takeAbsen").click(function (e) {
            e.preventDefault();
            // alert('ok');
            Webcam.snap(function(uri){
                image = uri;
            })

            let koordinat = $("#lokasi").val();
            $.ajax({
                type: "POST",
                url: "/presensi/store",
                data: {
                    _token:"{{ csrf_token() }}",
                    image:image,
                    lokasi:koordinat
                },
                cache:false,
                success: function (response) {
                    let status = response.split("|");
                    if(status[0] == "success"){
                        if(status[2] == "in"){
                            notif_in.play();
                        }else{
                            notif_out.play();
                        }
                        Swal.fire({
                            title   : "Success",
                            text    : status[1],
                            icon    : "success",

                        })
                        setTimeout("location.href= '/frontend/dashboards'",3000);
                    }else{
                        if(status[0] == "Error_radius"){
                            notif_error_radius.play();
                        }else{
                            notif_error.play();
                        }
                        Swal.fire({
                            title   : "Error",
                            text    :  status[1],
                            icon    : "error",

                        })
                        setTimeout("location.href= '/frontend/dashboards'",5000);
                    }
                }
            });

        });

    </script>
@endpush
