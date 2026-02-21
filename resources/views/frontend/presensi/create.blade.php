@extends('frontend.layout.app')
@section('title', 'Attendance')

@section('header')
    <div class="appHeader bg-primary text-light border-0">
        <div class="left">
            <a href="/frontend/dashboards" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>

        <div class="pageTitle d-flex align-items-center justify-content-center">
            <span class="text-uppercase fw-bold" style="letter-spacing: 1px; margin-left: -30px;">Live Attendance</span>
        </div>

        <div class="right">
            <div id="jam"
                style="
            background: rgba(255, 152, 0, 0.9);
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            display: flex;
            align-items: center;
            gap: 4px;
        ">
                <ion-icon name="time" style="font-size: 14px;"></ion-icon>
                <span id="time-text">00:00:00</span>
            </div>
        </div>
    </div>

    <style>
        /* Container Adjustment */
        #appCapsule {
            padding-top: 20px;
            padding-bottom: 24px;
            background: #f8f9fa;
        }

        .appHeader .pageTitle {
            /* Memastikan judul tetap di kiri dan jam di kanan dalam satu baris */
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        /* Hilangkan padding default agar tidak terlalu mepet ke atas */
        .appHeader {
            display: flex;
            align-items: center;
        }

        /* Menghilangkan border default badge jika masih terbawa */
        .badge-warning {
            background-color: transparent !important;
        }

        .appHeader .right {
            display: flex;
            align-items: center;
            padding-right: 10px;
        }

        .webcam-capture,
        .webcam-capture video {
            width: 100% !important;
            /* Gunakan aspect-ratio 16:9 agar lebih lebar ke samping (seperti layar bioskop) */
            aspect-ratio: 16 / 9;
            height: auto !important;
            /* Batasi tinggi maksimal agar tidak menelan layar di HP kecil */
            max-height: 250px !important;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: scaleX(-1);
            /* Efek cermin */
        }

        /* Kurangi margin pada card agar tidak terlalu menumpuk ke kamera yang sudah pendek */
        .attendance-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 15px;
            padding: 15px;
            margin-top: -20px;
            /* Ubah dari -60px ke -20px */
            position: relative;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .shift-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 700;
            letter-spacing: 1px;
            display: block;
        }

        /* Tombol Modern */
        .btn-attendance {
            height: 50px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
        }

        .btn-attendance:active {
            transform: scale(0.97);
        }

        /* Map UI */
        /* Map: Perkecil sedikit tingginya untuk mobile */
        #map {
            height: 180px;
            /* Kurangi dari 220px agar hemat ruang */
            width: 100%;
            border-radius: 15px;
        }

        /* Status Dot Animation */
        .status-dot {
            width: 8px;
            height: 8px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(46, 204, 113, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }
    </style>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="section full p-2">
            <input type="hidden" id="lokasi">
            <div class="webcam-capture"></div>
        </div>

        <div class="section px-3">
            <div class="attendance-card">
                <div class="row align-items-center">
                    <div class="col-7 border-end">
                        <span class="shift-label">Shift Aktif</span>
                        <h4 class="fw-bold mb-1 text-primary">{{ $cekjamkerjadept->shift_name }}</h4>
                        <div class="d-flex align-items-center">
                            <span class="status-dot"></span>
                            <span class="small text-muted">{{ date('d M Y') }}</span>
                        </div>
                    </div>
                    <div class="col-5 ps-3">
                        <div class="mb-1">
                            <span class="text-muted small d-block">Masuk</span>
                            <span
                                class="fw-bold text-dark">{{ date('H:i', strtotime($cekjamkerjadept->entry_time)) }}</span>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Pulang</span>
                            <span class="fw-bold text-dark">{{ date('H:i', strtotime($cekjamkerjadept->out_time)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-3 px-3">
            @if ($cek > 0)
                <button id="takeAbsen" class="btn btn-danger btn-block btn-attendance">
                    <ion-icon name="log-out-outline" style="font-size: 24px;"></ion-icon>
                    KONFIRMASI ABSEN PULANG
                </button>
            @else
                <button id="takeAbsen" class="btn btn-primary btn-block btn-attendance">
                    <ion-icon name="camera-outline" style="font-size: 24px;"></ion-icon>
                    KONFIRMASI ABSEN MASUK
                </button>
            @endif
        </div>
        <div class="section mt-2 px-2 text-center">
            @if ($is_free_absen)
                <span class="badge badge-success px-3 py-1" style="border-radius: 20px;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon> Mode Free Absensi Aktif
                </span>
            @endif
        </div>

        <div class="section mt-4 px-3">
            <div class="d-flex justify-content-between align-items-end mb-1">
                <h6 class="fw-bold mb-0"><ion-icon name="location-outline"></ion-icon> Lokasi Anda</h6>
                <span class="text-primary small fw-bold">GPS Aktif</span>
            </div>
            <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                <div id="map"></div>
            </div>
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
{{-- @push('scripts')
    <script>
        // Konfigurasi Kamera
        Webcam.set({
            width: 640,
            height: 480, // Resolusi internal (tidak mempengaruhi ukuran tampilan CSS)
            image_format: "jpeg",
            jpeg_quality: 80,
            constraints: {
                facingMode: 'user'
            }
        });
        Webcam.attach('.webcam-capture');

        var lokasiInput = document.getElementById('lokasi');
        var map = null; // Inisialisasi variabel global

        // 1. Jalankan GPS
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                enableHighAccuracy: true,
                timeout: 5000
            });
        }

        function successCallback(position) {
            var userLat = position.coords.latitude;
            var userLong = position.coords.longitude;
            lokasiInput.value = userLat + "," + userLong;

            // 2. CEK & HAPUS MAP LAMA (PENTING: Mencegah Error Initialized)
            if (map !== null) {
                map.remove();
            }

            // 3. INISIALISASI MAP BARU
            map = L.map('map', {
                zoomControl: false
            }).setView([userLat, userLong], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // 4. ICON USER (LOKASI DEVICE)
            var userIcon = L.divIcon({
                className: 'user-icon',
                html: '<div style="background:#6236FF;width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 10px rgba(0,0,0,0.5);"></div>',
                iconSize: [14, 14]
            });

            var userMarker = L.marker([userLat, userLong], {
                icon: userIcon
            }).addTo(map);

            // 5. AMBIL DATA DARI CONTROLLER
            var isFreeAbsen = @json($is_free_absen);
            var locations = @json($employee_locations);
            var bounds = [];
            bounds.push([userLat, userLong]);

            // Popup Jika Free Absen
            if (isFreeAbsen == 1 || isFreeAbsen == true) {
                userMarker.bindPopup("<b>Mode Free Absensi Aktif</b>").openPopup();
            }

            // 6. GAMBAR RADIUS & LOKASI KANTOR
            // Ganti bagian looping locations di JavaScript Anda menjadi seperti ini:
            if (locations && locations.length > 0) {
                locations.forEach(function(loc) {
                    // Ambil data langsung dari property latitude & longitude
                    var latOffice = parseFloat(loc.latitude);
                    var longOffice = parseFloat(loc.longitude);
                    var radOffice = parseInt(loc.radius);

                    if (!isNaN(latOffice) && !isNaN(longOffice)) {
                        // Gambar Radius Merah di Peta
                        L.circle([latOffice, longOffice], {
                            color: '#FF396F',
                            fillColor: '#FF396F',
                            fillOpacity: 0.2,
                            radius: radOffice
                        }).addTo(map);

                        // Tambahkan Marker Kantor
                        L.marker([latOffice, longOffice]).addTo(map)
                            .bindPopup("<b>" + loc.name + "</b><br>Radius: " + radOffice + "m");
                    }
                });
            }
        }

        function errorCallback(error) {
            Swal.fire("GPS Error", "Harap aktifkan GPS Anda dan izinkan akses lokasi.", "error");
        }

        // Script Simpan Absen (Ajax)
        $("#takeAbsen").click(function(e) {
            e.preventDefault();
            let btn = $(this);
            let originalText = btn.html();

            Webcam.snap(function(uri) {
                image = uri;
            });
            let koordinat = $("#lokasi").val();

            if (!koordinat) {
                Swal.fire("Gagal", "Lokasi GPS belum terdeteksi. Harap tunggu.", "warning");
                return;
            }

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> MEMPROSES...');

            $.ajax({
                type: "POST",
                url: "/presensi/store",
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: koordinat
                },
                success: function(response) {
                    let status = response.split("|");
                    if (status[0] == "success") {
                        status[2] == "in" ? document.getElementById('notif_in').play() : document
                            .getElementById('notif_out').play();
                        Swal.fire({
                                title: "Success",
                                text: status[1],
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            })
                            .then(() => {
                                location.href = '/frontend/dashboards';
                            });
                    } else {
                        status[0] == "Error_radius" ? document.getElementById('notif_error_radius')
                            .play() : document.getElementById('notif_error').play();
                        Swal.fire("Gagal", status[1], "error");
                        btn.prop('disabled', false).html(originalText);
                    }
                }
            });
        });

        function jam() {
            var e = document.getElementById('time-text');
            if (!e) return;
            var d = new Date(),
                h = String(d.getHours()).padStart(2, '0'),
                m = String(d.getMinutes()).padStart(2, '0'),
                s = String(d.getSeconds()).padStart(2, '0');
            e.innerText = h + ':' + m + ':' + s;
            setTimeout(jam, 1000);
        }
        document.addEventListener('DOMContentLoaded', jam);
    </script>
@endpush --}}
@push('scripts')
    <script>
        // 1. Inisialisasi Lokasi & Map
        var lokasiInput = document.getElementById('lokasi');
        var map = null;

        // 2. Fungsi Kamera (Dibuat Fungsi agar bisa dipanggil ulang)
        function initWebcam() {
            Webcam.set({
                width: 640,
                height: 480,
                image_format: "jpeg",
                jpeg_quality: 80,
                constraints: {
                    facingMode: 'user'
                }
            });

            Webcam.attach('.webcam-capture');

            // Dengarkan jika ada error kamera
            Webcam.on('error', function(err) {
                console.log("Webcam Error: " + err);
                // Jika error, coba attach lagi setelah 2 detik
                setTimeout(function() { Webcam.attach('.webcam-capture'); }, 2000);
            });
        }

        // 3. Fungsi GPS dengan Logika "Retry"
        function getGeolocation() {
            if (navigator.geolocation) {
                console.log("Memulai pencarian GPS...");
                navigator.geolocation.getCurrentPosition(successCallback, function(error) {
                    console.warn("GPS Gagal (Percobaan 1), Mencoba lagi...");

                    // Percobaan kedua dengan timeout lebih lama
                    navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    });
                }, {
                    enableHighAccuracy: true,
                    timeout: 7000
                });
            }
        }

        // 4. Jalankan Semua Saat Halaman Siap
        document.addEventListener('DOMContentLoaded', function() {
            initWebcam();
            // Beri jeda 2 detik sebelum minta GPS agar hardware kamera stabil dulu
            setTimeout(getGeolocation, 2000);
            jam();
        });

        function successCallback(position) {
            console.log("GPS Berhasil!");
            var userLat = position.coords.latitude;
            var userLong = position.coords.longitude;
            lokasiInput.value = userLat + "," + userLong;

            if (map !== null) { map.remove(); }

            map = L.map('map', { zoomControl: false, attributionControl: false }).setView([userLat, userLong], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            var userIcon = L.divIcon({
                className: 'user-icon',
                html: '<div style="background:#6236FF;width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 10px rgba(0,0,0,0.5);"></div>',
                iconSize: [14, 14]
            });
            L.marker([userLat, userLong], { icon: userIcon }).addTo(map);

            // Tambahkan Radius Kantor
            var locations = @json($employee_locations);
            if (locations) {
                locations.forEach(function(loc) {
                    L.circle([loc.latitude, loc.longitude], {
                        color: '#FF396F', fillColor: '#FF396F', fillOpacity: 0.2, radius: parseInt(loc.radius)
                    }).addTo(map);
                });
            }
        }

        function errorCallback(error) {
            console.error("GPS Error Final: ", error);
            // Hanya munculkan Swal jika benar-benar gagal setelah retry
            Swal.fire({
                title: "GPS Belum Terkunci",
                text: "Pastikan GPS aktif. Coba refresh halaman atau tunggu 5 detik.",
                icon: "warning",
                confirmButtonText: "Coba Lagi"
            }).then((result) => {
                if (result.isConfirmed) { getGeolocation(); }
            });
        }

        // ... (Script Simpan Absen tetap gunakan AJAX yang lama) ...
    </script>
@endpush
