@extends('frontend.layout.app')
@section('title','Form Izin Sakit')
@section('header')
{{-- datepicker --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css" rel="stylesheet"> --}}
<style>
    .datepicker-modal{
        max-height: 500px !important;
    }

    .datepicker-date-display{
        background-color: #0d6efd !important;
    }

    .datepicker-cancel, .datepicker-clear, .datepicker-today, .datepicker-done {
        color: #0d6efd;
        padding: 0 1rem;
    }


    .fl-wrapper {
        position: fixed;
        -webkit-transition: all 1s ease-in-out;
        -moz-transition: all 1s ease-in-out;
        transition: all 1s ease-in-out;
        width: 24em;
        z-index: 100000000000 !important;
    }
    #ket{
        height: 150px;
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
        <form action="{{route('submissions.storesakit')}}" method="POST" enctype="multipart/form-data" id="frmIzin" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" placeholder="Tanggal" name="tgl_izin" id="tgl_izin" >
                    </div>
                    <div class="mt-2">
                        <strong>Total: <span id="jmlHari">0</span> Hari</strong>
                        <input type="hidden" name="jml_hari" id="jml_hari_input" value="0">
                    </div>
                    <div class="form-group mt-2">
                        <select name="status" id="status" class="form-control">
                            <option value="">Pilih Jenis Pengajuan</option>
                            <option value="2">Sakit</option>
                            <option value="3">Sakit Dokter</option>
                        </select>
                    </div>
                    <div class="custom-file-upload" id="fileUpload1">
                        <input type="file" name="photo" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                        <label for="fileuploadInput">
                            <span>
                                <strong>
                                    <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                                    <i>Upload Document</i>
                                </strong>
                            </span>
                        </label>
                    </div>
                    <div class="from-group">
                        <textarea name="ket" id="ket" cols="30" rows="5" class="form-group" placeholder="Keterangan"></textarea>
                    </div>

                    <div class="from-group">
                        <button type="submit" class="btn btn-success w-100" id="kirim">Kirim</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
    var currYear = (new Date()).getFullYear();

    $(document).ready(function() {
        flatpickr("#tgl_izin", {
            mode: "range",
            dateFormat: "d-m-Y",
            onChange: function(selectedDates, dateStr, instance) {
                // --- LOGIKA HITUNG HARI ---
                let diffDays = 0;
                if (selectedDates.length === 2) {
                    const diffTime = Math.abs(selectedDates[1] - selectedDates[0]);
                    diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                } else if (selectedDates.length === 1) {
                    diffDays = 1;
                }
                // Update teks jumlah hari di UI
                $("#jmlHari").text(diffDays);           // Untuk tampilan di layar
                $("#jml_hari_input").val(diffDays);     // Untuk dikirim ke database
            },
            onClose: function(selectedDates, dateStr, instance) {
                // --- LOGIKA AJAX CEK TANGGAL (Hanya jalan jika sudah pilih tanggal) ---
                if (dateStr !== "") {
                    $.ajax({
                        type: "POST",
                        url: "/submissions/cektglpengajuan",
                        data: {
                            _token: "{{csrf_token()}}",
                            tgl_izin: dateStr,
                        },
                        success: function(response) {
                            // Jika response > 0 berarti sudah ada pengajuan
                            if (response > 0) {
                                Swal.fire({
                                    title: 'Oops',
                                    text: 'Anda Sudah Melakukan Pengajuan pada rentang tanggal tersebut',
                                    icon: 'warning',
                                }).then((result) => {
                                    // Reset input dan jumlah hari jika bentrok
                                    instance.clear();
                                    $("#jmlHari").text(0);
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error("Error checking date:", xhr.responseText);
                        }
                    });
                }
            }
        });
    });

    // defaulf disable upload file
    $('#fileUpload1').fadeOut();

    // Jalankan fungsi saat dropdown status berubah
    $('#status').change(function() {
        // alert('ok');
        // die();
        var status = $(this).val();

        if (status == "3") {
            // Tampilkan dengan efek fade agar lebih halus
            $('#fileUpload1').fadeIn();
        } else {
            // Sembunyikan jika memilih Sakit biasa (value 2)
            $('#fileUpload1').fadeOut();
            // Opsional: Hapus file yang sudah dipilih jika user berubah pikiran
            $('#fileuploadInput').val('');
        }
    });

    $('#frmIzin').submit(function(e){
        var tgl_izin=$('#tgl_izin').val();
        var status=$('#status').val();
        var keterangan=$('#ket').val();
        var uploadFile= $('#fileuploadInput').val();
        if(tgl_izin == ""){
            Swal.fire({
                title: 'Oops',
                text: 'Tanggal Harus Diisi',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        }else if(status == ""){
            Swal.fire({
                title: 'Oops',
                text: ' Jenis Pengajuan Harus Dipilih',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        }
        else if(status == "3" && uploadFile == ""){
            Swal.fire({
                title: 'Oops',
                text: 'Surat Dokter Harus Disertakan',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        }
        else if (keterangan=="") {
            Swal.fire({
                title: 'Oops',
                text: 'Keterangan Harus Diisi',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        };
    })

</script>
@endpush

