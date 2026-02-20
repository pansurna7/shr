@extends('frontend.layout.app')
@section('title','Pengajuan Izin')
@section('header')
<style>
    /* Custom Styling for Professional Look */
    .form-container {
        margin-top: 3.5rem;
        padding: 15px;
    }
    .card-custom {
        border: none;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        background: #ffffff;
    }
    .input-wrapper {
        position: relative;
        margin-bottom: 20px;
    }
    .input-wrapper label {
        font-size: 11px;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
        display: block;
        padding-left: 4px;
    }
    .custom-input {
        border-radius: 16px !important;
        border: 1.5px solid #f1f3f5 !important;
        padding: 12px 16px 12px 45px !important;
        height: auto !important;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #f8f9fa !important;
    }
    .custom-input:focus {
        background-color: #ffffff !important;
        border-color: #4e73df !important;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1) !important;
    }
    .input-icon {
        position: absolute;
        left: 16px;
        top: 38px;
        font-size: 20px;
        color: #4e73df;
        z-index: 10;
    }
    .days-badge {
        display: inline-flex;
        align-items: center;
        background: #eef2ff;
        color: #4e73df;
        padding: 6px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        margin-top: 10px;
    }
    .submit-btn {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        border-radius: 16px;
        padding: 14px;
        font-weight: 700;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        margin-top: 10px;
    }
    .submit-btn:active {
        transform: scale(0.98);
    }
    /* Flatpickr Customization */
    .flatpickr-calendar {
        border-radius: 18px !important;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
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
<div class="form-container">
    <div class="card card-custom">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary-light rounded-circle"
                     style="width: 60px; height: 60px; background: #f0f3ff;">
                    <ion-icon name="mail-open-outline" style="font-size: 30px; color: #4e73df;"></ion-icon>
                </div>
                <h4 class="fw-bold mt-2 mb-0" style="color: #2d3436;">Formulir Izin</h4>
                <p class="text-muted small">Lengkapi detail pengajuan di bawah ini</p>
            </div>

            <form action="{{route('submission.store')}}" method="POST" enctype="multipart/form-data" id="frmIzin" autocomplete="off">
                @csrf

                <div class="input-wrapper">
                    <label>Pilih Rentang Tanggal</label>
                    <ion-icon name="calendar-outline" class="input-icon"></ion-icon>
                    <input type="text" name="tgl_izin" id="tgl_izin" class="form-control custom-input datepicker"
                           placeholder="Pilih tanggal mulai & berakhir" readonly>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="days-badge">
                            <ion-icon name="time-outline" class="mr-1"></ion-icon>
                            <span id="jmlHari">0</span> &nbsp;Hari Kerja
                        </div>
                        <input type="hidden" name="jml_hari" id="jml_hari_input" value="0">
                    </div>
                </div>

                <div class="input-wrapper">
                    <label>Alasan Pengajuan</label>
                    <ion-icon name="chatbox-ellipses-outline" class="input-icon"></ion-icon>
                    <textarea name="ket" id="ket" rows="4" class="form-control custom-input"
                              style="padding-left: 45px !important;"
                              placeholder="Contoh: Keperluan keluarga mendesak..."></textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-block submit-btn">
                        <ion-icon name="paper-plane-outline" class="mr-1"></ion-icon>
                        Kirim Sekarang
                    </button>
                    <a href="{{ route('presensi.izin') }}" class="btn btn-link btn-block text-muted mt-2 small">Kembali</a>
                </div>

                <input type="hidden" name="status" value="0">
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Flatpickr Modern
        flatpickr("#tgl_izin", {
            mode: "range",
            dateFormat: "d-m-Y",
            disableMobile: "true",
            locale: { rangeSeparator: "  to  " },
            onChange: function(selectedDates, dateStr, instance) {
                let diffDays = 0;
                if (selectedDates.length === 2) {
                    const diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                    diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                } else if (selectedDates.length === 1) {
                    diffDays = 1;
                }
                $("#jmlHari").text(diffDays);
                $("#jml_hari_input").val(diffDays);
            },
            onClose: function(selectedDates, dateStr, instance) {
    if (dateStr !== "") {
        $.ajax({
            type: "POST",
            url: "/submissions/cektglpengajuan",
            data: {
                _token: "{{csrf_token()}}",
                tgl_izin: dateStr,
            },
            success: function(response) {
                // response sekarang berupa OBJECT {status: "error", message: "..."}
                if (response.status === 'error') {
                    Swal.fire({
                        title: 'Permintaan Ditolak',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#4e73df'
                    }).then((result) => {
                        // Reset input agar user tidak bisa memaksakan tanggal tersebut
                        instance.clear();
                        $("#jmlHari").text(0);
                        $("#jml_hari_input").val(0);
                    });
                }
            }
        });
    }
}
        });

        // Form Submit dengan Swal yang lebih halus
        $('#frmIzin').submit(function(e) {
            e.preventDefault();
            var tgl = $('#tgl_izin').val();
            var ket = $('#ket').val();

            if(!tgl) {
                Swal.fire({ icon: 'error', title: 'Kosong', text: 'Pilih tanggal terlebih dahulu', confirmButtonColor: '#4e73df' });
                return false;
            }
            if(!ket) {
                Swal.fire({ icon: 'error', title: 'Kosong', text: 'Berikan alasan pengajuan Anda', confirmButtonColor: '#4e73df' });
                return false;
            }

            Swal.fire({
                title: 'Kirim Pengajuan?',
                text: "Data akan segera diproses oleh pihak HRD.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#f1f2f6',
                confirmButtonText: '<span style="color:white">Ya, Kirim</span>',
                cancelButtonText: '<span style="color:#596275">Batal</span>',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
