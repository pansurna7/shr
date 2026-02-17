@extends('frontend.layout.app')
@section('title','Form Pengajuan Sakit')
@section('header')
<style>
    .form-container {
        margin-top: 4rem;
        padding: 0 16px 20px 16px;
    }
    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        background: #ffffff;
        overflow: hidden;
    }
    .input-group-custom {
        margin-bottom: 20px;
    }
    .input-group-custom label {
        font-size: 12px;
        font-weight: 700;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .form-control-custom {
        border-radius: 12px !important;
        border: 1.5px solid #edf2f7 !important;
        padding: 12px 15px !important;
        height: auto !important;
        font-size: 15px;
        background-color: #f8fafc !important;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        border-color: #4e73df !important;
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1) !important;
    }
    /* Style khusus untuk upload file */
    .file-upload-wrapper {
        position: relative;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        transition: all 0.3s ease;
    }
    .file-upload-wrapper.active {
        border-color: #4e73df;
        background: #eef2ff;
    }
    .btn-submit {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border: none;
        border-radius: 12px;
        padding: 14px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.2);
    }
    .days-badge {
        display: inline-block;
        padding: 4px 12px;
        background: #eef2ff;
        color: #4e73df;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
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
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background: #fff5f5;">
                    <ion-icon name="medkit-outline" style="font-size: 28px; color: #e53e3e;"></ion-icon>
                </div>
                <h5 class="fw-bold mt-2 mb-0">Informasi Kesehatan</h5>
                <p class="text-muted small">Silakan lengkapi detail istirahat Anda</p>
            </div>

            <form action="{{route('submissions.storesakit')}}" method="POST" enctype="multipart/form-data" id="frmIzin" autocomplete="off">
                @csrf

                <div class="input-group-custom">
                    <label>Rentang Tanggal Sakit</label>
                    <input type="text" class="form-control form-control-custom datepicker" placeholder="Pilih Tanggal..." name="tgl_izin" id="tgl_izin">
                    <div class="mt-2 text-end">
                        <div class="days-badge">
                            Total: <span id="jmlHari">0</span> Hari
                        </div>
                        <input type="hidden" name="jml_hari" id="jml_hari_input" value="0">
                    </div>
                </div>

                <div class="input-group-custom">
                    <label>Kategori</label>
                    <select name="status" id="status" class="form-control form-control-custom">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="2">Sakit (Tanpa Surat)</option>
                        <option value="3">Sakit (Dengan Surat Dokter)</option>
                    </select>
                </div>

                <div class="input-group-custom" id="fileUploadContainer" style="display: none;">
                    <label>Dokumen Surat Dokter</label>
                    <div class="file-upload-wrapper" id="uploadArea">
                        <input type="file" name="photo" id="fileuploadInput" accept=".png, .jpg, .jpeg" style="display: none;">
                        <label for="fileuploadInput" style="cursor: pointer; margin-bottom: 0;">
                            <ion-icon name="cloud-upload-outline" style="font-size: 32px; color: #4e73df;"></ion-icon>
                            <p class="mb-0 small fw-bold text-primary">Klik untuk Upload Surat Dokter</p>
                            <p class="text-muted" style="font-size: 10px;" id="fileNameDisplay">Format: JPG, PNG (Max 2MB)</p>
                        </label>
                    </div>
                </div>

                <div class="input-group-custom">
                    <label>Keluhan / Keterangan</label>
                    <textarea name="ket" id="ket" rows="3" class="form-control form-control-custom" placeholder="Tuliskan keluhan singkat Anda..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-submit">
                    <ion-icon name="send-outline" class="me-1"></ion-icon>
                    Kirim Pengajuan
                </button>
                <a href="{{ route('presensi.izin') }}" class="btn btn-link btn-block text-muted mt-2 small">Batalkan</a>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Flatpickr
        flatpickr("#tgl_izin", {
            mode: "range",
            dateFormat: "d-m-Y",
            locale: { rangeSeparator: " to " },
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
                        data: { _token: "{{csrf_token()}}", tgl_izin: dateStr },
                        success: function(response) {
                            if (response.status === 'error') {
                                Swal.fire({ icon: 'error', title: 'Opps!', text: response.message, confirmButtonColor: '#4e73df' });
                                instance.clear();
                                $("#jmlHari").text(0);
                            }
                        }
                    });
                }
            }
        });

        // Toggle Upload File
        $('#status').change(function() {
            if ($(this).val() == "3") {
                $('#fileUploadContainer').slideDown();
            } else {
                $('#fileUploadContainer').slideUp();
                $('#fileuploadInput').val('');
                $('#fileNameDisplay').text('Format: JPG, PNG (Max 2MB)');
            }
        });

        // Display File Name on Change
        $('#fileuploadInput').change(function() {
            let filename = $(this).val().split('\\').pop();
            if (filename) {
                $('#fileNameDisplay').text('File: ' + filename).addClass('text-success fw-bold');
                $('#uploadArea').addClass('active');
            }
        });

        // Submit Validation
        $('#frmIzin').submit(function(e) {
            e.preventDefault();
            let tgl = $('#tgl_izin').val();
            let status = $('#status').val();
            let ket = $('#ket').val();
            let file = $('#fileuploadInput').val();

            if (!tgl) return Swal.fire('Opps', 'Pilih tanggal terlebih dahulu', 'warning');
            if (!status) return Swal.fire('Opps', 'Pilih kategori pengajuan', 'warning');
            if (status == "3" && !file) return Swal.fire('Opps', 'Mohon lampirkan Surat Dokter', 'warning');
            if (!ket) return Swal.fire('Opps', 'Berikan keterangan sakit Anda', 'warning');

            Swal.fire({
                title: 'Kirim Pengajuan?',
                text: "Pastikan data sudah benar sebelum dikirim.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4e73df',
                cancelButtonColor: '#f1f2f6',
                confirmButtonText: '<span style="color:#fff">Ya, Kirim</span>',
                cancelButtonText: '<span style="color:#596275">Batal</span>'
            }).then((result) => {
                if (result.isConfirmed) this.submit();
            });
        });
    });
</script>
@endpush
