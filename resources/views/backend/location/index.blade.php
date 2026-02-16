@extends('backend.layouts.app')
@section('title','Location/Map')

@push('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
   #map-monitoring {
    height: 400px;
    width: 100%;
    border-radius: 12px;
    border: 1px solid #ddd;
    /* Tambahkan ini untuk memastikan layer Leaflet tidak kacau */
    z-index: 1 !important;
}

/* Memperbaiki glitch ubin peta yang pecah */
.leaflet-container {
    height: 100%;
    width: 100%;
}
    #modal-map { height: 400px; width: 100%; border-radius: 8px; border: 1px solid #ddd; cursor: crosshair; }
    .text-bold { font-weight: bold; }

    #modal-add-location .modal-content { background-color: #1a1a1a !important; border-radius: 15px; border: none; }
    #modal-add-location .modal-header { background: linear-gradient(45deg, #4e73df, #224abe) !important; border-bottom: none; border-top-left-radius: 15px; border-top-right-radius: 15px; }
    #modal-add-location .modal-body { background: rgba(30, 30, 30, 1) !important; color: #ffffff !important; }
    #modal-add-location .modal-footer { background: #252525 !important; border-top: 1px solid #333; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; }
    #modal-add-location .form-label { color: #ffffff !important; }
    .modal-backdrop { background-color: #000; opacity: 0.8 !important; }
</style>
@endpush

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Location List</div>
        <div class="ms-auto">
            @can('location.create')
                <button type="button" class="btn btn-primary" id="btnCreate">
                    <i class="bx bx-plus-circle"></i> Add New Location
                </button>
            @endcan
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12"><div class="card shadow-sm"><div class="card-body"><div id="map-monitoring"></div></div></div></div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="tblLocations">
                        <thead>
                            <tr>
                                <th>Location Name</th>
                                <th>Coordinates</th>
                                <th>Radius</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $loc)
                            <tr>
                                <td><strong>{{ $loc->name }}</strong><br><small class="text-muted">{{ $loc->address }}</small></td>
                                <td><code>{{ $loc->latitude }}, {{ $loc->longitude }}</code></td>
                                <td>{{ $loc->radius }} m</td>
                                <td><span class="badge {{ $loc->is_active ? 'bg-success' : 'bg-danger' }}">{{ $loc->is_active ? 'Active' : 'Inactive' }}</span></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info view-on-map text-white" data-lat="{{ $loc->latitude }}" data-lng="{{ $loc->longitude }}">
                                            <i class="bx bx-map-pin"></i>
                                        </button>
                                        @can('location.edit')
                                            <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $loc->id }}">
                                                <i class="bx bx-edit text-white"></i>
                                            </button>
                                        @endcan
                                        @can('location.delete')
                                            <button type="button" class="btn btn-sm btn-danger delete-button" data-id="{{ $loc->id }}">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        @endcan
                                            <form id="delete-form-{{ $loc->id }}" action="{{ route('location.destroy', $loc->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE') </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-add-location" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalTitle">Create New Location Point</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="form-location" action="{{ route('location.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-bold">Location Name</label>
                                    <input type="text" id="m-name" name="name" class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-bold">Latitude</label>
                                        <input type="text" id="m-lat" name="latitude" class="form-control bg-dark text-white coord-input" required>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label text-bold">Longitude</label>
                                        <input type="text" id="m-lng" name="longitude" class="form-control bg-dark text-white coord-input" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-bold">Radius (Meter)</label>
                                    <input type="number" id="m-radius_input" name="radius" class="form-control" value="20">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-bold">Full Address</label>
                                    <textarea id="m-address" name="address" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="modal-map"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="btn-submit-location" class="btn btn-primary px-4">Save Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    var modalMap, modalMarker, modalCircle, mainMap;

    function updateModalFields(lat, lng, skipGeocode = false) {
        $('#m-lat').val(parseFloat(lat).toFixed(8));
        $('#m-lng').val(parseFloat(lng).toFixed(8));
        if (modalMarker) {
            modalMarker.setLatLng([lat, lng]);
            modalCircle.setLatLng([lat, lng]);
        }
        if (!skipGeocode) reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        $.getJSON(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, function(data) {
            if (data && data.display_name) $('#m-address').val(data.display_name);
        });
    }

    $(document).ready(function() {
        new DataTable('#tblLocations');

       // --- 2. MAP MONITORING (Peta Utama di Index) ---
        var monitoringEl = document.getElementById('map-monitoring');
        if (monitoringEl) {
            // Inisialisasi peta dengan view default (Jakarta)
            mainMap = L.map('map-monitoring').setView([-6.200000, 106.816666], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mainMap);

            // Buat grup untuk menampung semua marker agar bisa di-fitBounds
            var markerGroup = new L.featureGroup();

            @foreach($locations as $loc)
                @if($loc->latitude && $loc->longitude)
                    // Tambahkan Marker
                    var marker = L.marker([{{ $loc->latitude }}, {{ $loc->longitude }}])
                        .bindPopup("<b>{{ $loc->name }}</b><br>{{ $loc->address }}")
                        .addTo(markerGroup);

                    // Tambahkan Radius Circle
                    L.circle([{{ $loc->latitude }}, {{ $loc->longitude }}], {
                        color: '#3498db',
                        fillOpacity: 0.2,
                        radius: {{ $loc->radius ?? 100 }}
                    }).addTo(mainMap);
                @endif
            @endforeach

            // Masukkan semua marker ke peta
            markerGroup.addTo(mainMap);

            // OTOMATIS ZOOM KE SEMUA TITIK
            if (markerGroup.getLayers().length > 0) {
                mainMap.fitBounds(markerGroup.getBounds(), { padding: [50, 50] });
            }
        }

        // --- BUTTON ADD ---
        $("#btnCreate").click(function () {
            $('#form-location').attr('action', "{{ route('location.store') }}");
            $('#form-method').val('POST');
            $('#modalTitle').text('Create New Location Point');
            $('#form-location')[0].reset();
            new bootstrap.Modal(document.getElementById('modal-add-location')).show();
        });

        // --- BUTTON EDIT (AJAX) ---
        $(document).on('click', '.btnEdit', function() {
            var id = $(this).data('id');
            var editUrl = "{{ url('location/edit') }}/" + id;

            $.get(editUrl, function(data) {
                // 1. Sinkronisasi URL & Method Form
                $('#form-location').attr('action', "{{ url('location/update') }}/" + id);
                $('#form-method').val('PUT');

                // 2. Update UI (Judul & Tombol)
                $('#modalTitle').text('Edit Location: ' + data.name);
                $('#form-location button[type="submit"]').text('Update Location');

                // 3. Isi Field Input
                $('#m-name').val(data.name);
                $('#m-lat').val(data.latitude);
                $('#m-lng').val(data.longitude);
                $('#m-radius_input').val(data.radius);
                $('#m-address').val(data.address);

                // 4. Tampilkan Modal
                var modalEl = document.getElementById('modal-add-location');
                var myModal = new bootstrap.Modal(modalEl);
                myModal.show();

                // 5. SINKRONISASI PETA (Setelah Modal Muncul)
                // Gunakan timeout agar Leaflet menghitung ukuran container yang benar
                setTimeout(function() {
                    if (modalMap) {
                        modalMap.invalidateSize(); // Reset render peta agar tidak meleset

                        var lat = parseFloat(data.latitude);
                        var lng = parseFloat(data.longitude);
                        var rad = parseInt(data.radius);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            // Geser Kamera Peta ke Koordinat Database
                            modalMap.setView([lat, lng], 17);

                            // Pindahkan Marker & Lingkaran ke Koordinat Database
                            if (modalMarker) modalMarker.setLatLng([lat, lng]);
                            if (modalCircle) {
                                modalCircle.setLatLng([lat, lng]);
                                modalCircle.setRadius(rad);
                            }
                        }
                    }
                }, 400); // Jeda 400ms adalah waktu ideal saat animasi modal bootstrap selesai
            });
        });

        $(document).on('click', '.view-on-map', function() {
            // 1. Ambil data latitude dan longitude dari atribut tombol
            var lat = $(this).data('lat');
            var lng = $(this).data('lng');

            // 2. Validasi apakah koordinat ada
            if (lat && lng) {
                // Fokuskan peta utama (mainMap) ke lokasi tersebut
                // Zoom diset ke 18 agar terlihat detail
                mainMap.setView([lat, lng], 18, {
                    animate: true,
                    pan: {
                        duration: 1 // durasi animasi 1 detik
                    }
                });

                // Opsional: Langsung buka popup marker di titik tersebut
                L.popup()
                    .setLatLng([lat, lng])
                    .setContent("Fokus pada lokasi ini")
                    .openOn(mainMap);

                // Scroll otomatis ke elemen peta agar user melihat perubahannya
                $('html, body').animate({
                    scrollTop: $("#map-monitoring").offset().top - 100
                }, 500);

            } else {
                alert("Koordinat tidak ditemukan untuk lokasi ini.");
            }
        });

        // --- MODAL MAP INIT ---
        $('#modal-add-location').on('shown.bs.modal', function () {
            if (!modalMap) {
                modalMap = L.map('modal-map').setView([-6.2, 106.8], 16);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(modalMap);
                modalMarker = L.marker([-6.2, 106.8], { draggable: true }).addTo(modalMap);
                modalCircle = L.circle([-6.2, 106.8], { radius: 20 }).addTo(modalMap);

                modalMap.on('click', (e) => updateModalFields(e.latlng.lat, e.latlng.lng));
                modalMarker.on('dragend', (e) => updateModalFields(e.target.getLatLng().lat, e.target.getLatLng().lng));
                $('#m-radius_input').on('input', function() { modalCircle.setRadius($(this).val()); });
            }
        });

        // --- QUICK PASTE & MANUAL INPUT ---
        $('.coord-input').on('input', function() {
            let val = $(this).val();
            if (val.includes(',')) {
                let s = val.split(',');
                updateModalFields(s[0], s[1]);
                modalMap.setView([s[0], s[1]], 17);
            }
        });

        $(document).on('click', '.delete-button', function (e) {
            e.preventDefault(); // Mencegah form submit langsung

            const itemId = $(this).data('id');
            const form = $(`#delete-form-${itemId}`);

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6', // Warna Biru (Confirm)
                cancelButtonColor: '#d33',    // Warna Merah (Cancel)
                confirmButtonText: 'Yes, delete it!',
                background: '#1a1a1a',        // Menyesuaikan tema dark modal Anda
                color: '#ffffff'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jalankan submit form jika user klik OK
                }
            });
        });
    });
</script>
@endpush
