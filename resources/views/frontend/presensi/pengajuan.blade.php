@extends('frontend.layout.app')
@section('title','Form Pengajuan Izin/Sakit')
@section('header')
{{-- datepicker --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css" rel="stylesheet">
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
        <form action="{{route('store.izin')}}" method="POST" enctype="multipart/form-data" id="frmIzin" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" placeholder="Tanggal" name="tgl_izin" id="tgl_izin" >
                    </div>
                    <div class="form-group">
                        <select name="status" id="status" class="form-control">
                            <option value="">Pilih Jenis Pengajuan</option>
                            <option value="1">Izin</option>
                            <option value="2">Sakit (Tanpa Surat Dokter)</option>
                            <option value="3">Sakit (Dengan Surat Dokter)</option>
                        </select>
                    </div>
                    <div class="from-group">
                        <textarea name="ket" id="ket" cols="30" rows="5" class="form-group" placeholder="Keterangan"></textarea>
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
        $(".datepicker").datepicker({
            format: "dd-mm-yyyy"
        });

        $("#tgl_izin").change(function (e) {
            // 1. Ambil nilai dengan sintaks jQuery yang benar: $(this).val()
            var tgl_pengajuan = $(this).val();
            $.ajax({
                type: "POST",
                url: "/presensi/cektglpengajuan",
                data: {
                        _token  :"{{csrf_token()}}",
                        tgl_izin:tgl_pengajuan,
                },
                cache: false,
                success: function (response) {
                    if(response == 1){
                        Swal.fire({
                            title: 'Oops',
                            text: 'Anda Sudah Melakukan Pengajuan',
                            icon: 'warning',
                            // confirmButtonText: 'OK'
                        }).then((result) => {
                            /* Read more about handling dismissals below */
                                $("#tgl_izin").val("");
                            });
                        return false;
                    }
                }
            });

        });
    });
    $('#frmIzin').submit(function(e){
        var tgl_izin=$('#tgl_izin').val();
        let status=$('#status').val();
        var keterangan=$('#ket').val();
        var fileUpload=$('#fileuploadInput').val();

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
                text: 'Jenis Pengajuan Harus Dipilih',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;

        }else if (keterangan=="") {
            Swal.fire({
                title: 'Oops',
                text: 'Keterangan Harus Diisi',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        }else if (status == 3 && fileUpload == "") {
            Swal.fire({
                title: 'Oops',
                text: 'Surat Keterangan Dokter Harus Diupload',
                icon: 'warning',
                // confirmButtonText: 'OK'
            })
            return false;
        };

    })

</script>
@endpush
