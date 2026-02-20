@extends('frontend.layout.app')
@section('title','Form Koreksi Jam Presensi')
@section('header')
{{-- datepicker --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css" rel="stylesheet"> --}}
<style>
    .fl-wrapper {
        position: fixed;
        -webkit-transition: all 1s ease-in-out;
        -moz-transition: all 1s ease-in-out;
        transition: all 1s ease-in-out;
        width: 24em;
        z-index: 100000000000 !important;
    }
    .form-control {
        border-color: #ebedf2;
        padding: 10px 15px;
    }
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: none;
    }
    .input-group-text {
        border-color: #ebedf2;
        color: #4e73df;
    }
    .fw-bold {
        font-weight: 700 !important;
    }
    /* Animasi saat tombol ditekan */
    .btn:active {
        transform: scale(0.98);
    }

</style>
    <div class="appHeader bg-primary text-align">
        <div class="left">
            <a href="{{route('presensi.izin')}}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">@yield('title')</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
<div class="row" style="margin-top: 4rem">
    <div class="col">
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: #eef2ff;">
                        <ion-icon name="time-outline" class="text-primary" style="font-size: 24px;"></ion-icon>
                    </div>
                    <div class="ml-3">
                        <h5 class="mb-0 fw-bold">Koreksi Absensi</h5>
                        <small class="text-muted">Ajukan perubahan jam masuk/pulang</small>
                    </div>
                </div>

                <form action="{{ route('submission.store_koreksi') }}" method="POST" id="frmKoreksi" autocomplete="off">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label small fw-bold text-muted">TANGGAL YANG DIKOREKSI</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;">
                                <ion-icon name="calendar-clear-outline" class="text-muted"></ion-icon>
                            </span>
                            <input type="date" name="tgl_koreksi" id="tgl_koreksi" class="form-control border-left-0" style="border-radius: 0 10px 10px 0;" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="form-label small fw-bold text-muted">JAM MASUK</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;">
                                        <ion-icon name="log-in-outline" class="text-success"></ion-icon>
                                    </span>
                                    <input type="time" name="jam_in" class="form-control border-left-0" style="border-radius: 0 10px 10px 0;">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-3">
                                <label class="form-label small fw-bold text-muted">JAM PULANG</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-right-0" style="border-radius: 10px 0 0 10px;">
                                        <ion-icon name="log-out-outline" class="text-danger"></ion-icon>
                                    </span>
                                    <input type="time" name="jam_out" class="form-control border-left-0" style="border-radius: 0 10px 10px 0;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label small fw-bold text-muted">ALASAN KOREKSI</label>
                        <textarea name="keterangan" rows="3" class="form-control"
                            style="border-radius: 10px;"
                            placeholder="Jelaskan alasan pengajuan (misal: Lupa absen, kendala sistem, tugas luar, dll)"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm" style="border-radius: 12px; height: 48px; font-weight: 600;">
                                <ion-icon name="send-outline" class="mr-2"></ion-icon>
                                Kirim Pengajuan
                            </button>
                            <a href="{{ route('presensi.izin') }}" class="btn btn-link btn-block text-muted mt-2 small">Batalkan</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var currYear = (new Date()).getFullYear();

   $(document).ready(function() {
    $('#tgl_koreksi').change(function() {
        let tgl = $(this).val(); // Ini adalah variabel yang benar
        let today = new Date().toISOString().split('T')[0];
        let $submitBtn = $('#frmKoreksi button[type="submit"]');

        // 1. Validasi Tanggal Masa Depan
        if (tgl > today) {
            Swal.fire('Opps!', 'Tidak bisa mengajukan koreksi untuk tanggal mendatang.', 'warning');
            $(this).val('');
            return;
        }

        // 2. Cek ke Server (Ganti dateStr menjadi tgl)
        if (tgl !== "") { // Perbaikan di sini
            $.ajax({
                type: 'POST',
                url: '/submissions/cektanggaltime',
                data: {
                    _token: "{{ csrf_token() }}",
                    tgl_izin: tgl
                },
                success: function(response) {
                    console.log("Response Full:", response); // Lihat di Console F12

                    if (response.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Permintaan Ditolak',
                            text: response.message,
                            confirmButtonColor: '#0d6efd'
                        });

                        $('#tgl_koreksi').val('').addClass('is-invalid');
                        $submitBtn.prop('disabled', true);
                    } else {
                        $('#tgl_koreksi').removeClass('is-invalid').addClass('is-valid');
                        $submitBtn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.error("Server Error:", xhr.responseText);
                }
            });
        }
    });
});

    $('#frmKoreksi').submit(function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Kirim Pengajuan?',
        text: "Pastikan jam yang diisi sudah sesuai dengan jadwal kerja Anda.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4e73df',
        cancelButtonColor: '#858796',
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Cek Kembali'
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    })
});

</script>
@endpush

