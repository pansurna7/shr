@extends('frontend.layout.app')
@section('title', 'Form Cuti')
@section('header')
    {{-- datepicker --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css" rel="stylesheet"> --}}
    <style>
        .selectmaterialize {
            display: block;
            background-color: transparent !important;
            border: 0px !important;
            border-bottom: 1px solid #9e9e9e !important;
            border-radius: 0 !important;
            outline: none !important;
            height: 3rem !important;
            width: 100% !important;
            font-size: 16px !important;
            margin: 0 0 8px 0 !important;
            padding: 0 !important;
            color: #495057;
        }

        .datepicker-modal {
            max-height: 500px !important;
        }

        .datepicker-date-display {
            background-color: #0d6efd !important;
        }

        .datepicker-cancel,
        .datepicker-clear,
        .datepicker-today,
        .datepicker-done {
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

        .form-control {
            border-radius: 8px !important;
            border: 1px solid #e0e0e0;
        }

        .card {
            border-radius: 12px !important;
        }

        textarea.form-control {
            padding: 10px;
        }
    </style>
    <div class="appHeader bg-primary text-align">
        <div class="left">
            <a href="{{ route('presensi.izin') }}" class="headerButton goBack">
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
            {{-- <form action="{{route('submission.storecuti')}}" method="POST" enctype="multipart/form-data" id="frmCuti" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col">
                     <div class="form-group mt-2">
                        <strong>Sisa Cuti: <span id="jmlCuti">0</span> Hari</strong>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control datepicker" placeholder="Tanggal" name="tgl_izin" id="tgl_izin" >
                    </div>
                    <div class="form-group mt-2">
                        <strong>Total: <span id="jmlHari">0</span> Hari</strong>
                        <input type="hidden" name="jml_hari" id="jml_hari_input" value="0">
                    </div>

                    <div class="form-group">
                        <select name="status" id="status" class="form-control" disabled hidden>
                            <option value="4">Cuti</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="jenis_cuti" id="jenis_cuti" class="form-control selectmaterialize">
                            <option value="">Pilih Jenis Cuti</option>
                            @foreach ($leaves as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="from-group">
                        <textarea name="ket" id="ket" cols="30" rows="5" class="form-group" placeholder="Keterangan"></textarea>
                    </div>

                    <div class="from-group">
                        <button type="submit" class="btn btn-success w-100" id="kirim">Kirim</button>
                    </div>
                </div>
            </div>
        </form> --}}
            <form action="{{ route('submission.storecuti') }}" method="POST" id="frmCuti" autocomplete="off">
                @csrf
                <div class="row">
                    <div class="col">

                        <div class="card border-0 shadow-sm bg-primary text-white mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="small opacity-75">Total Sisa Cuti Tersedia</span>
                                        <h2 class="mb-0 fw-bold">
                                            <span id="jmlCuti">{{ $total_sisa_cuti }}</span> Hari
                                        </h2>
                                    </div>
                                    <ion-icon name="hourglass-outline" style="font-size: 3rem; opacity: 0.3;"></ion-icon>
                                </div>

                                {{-- Alert Hangus Jika di Bulan Jan - Mar dan ada kuota tahun lalu --}}
                                @if (date('n') <= 3 && $kuota_lalu_aktif > 0)
                                    <div class="mt-2 p-2 bg-opacity-20 rounded"
                                        style="border: 1px dashed rgba(255,255,255,0.5)">
                                        <small style="font-size: 0.75rem; line-height: 1.2;">
                                            ⚠️ <strong>Perhatian!</strong><br>
                                            Sebanyak <strong>{{ $kuota_lalu_aktif }} hari</strong> adalah jatah tahun lalu
                                            yang akan <strong>hangus</strong> pada 31 Maret {{ date('Y') }}. Gunakan
                                            segera!
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($boleh_cuti)
                            <div class="form-group mb-2">
                                <label class="form-label small text-muted">Rentang Tanggal</label>
                                <input type="text" class="form-control datepicker" placeholder="Pilih Tanggal"
                                    name="tgl_izin" id="tgl_izin">
                            </div>

                            <div class="alert alert-info border-0 mt-2 d-flex justify-content-between align-items-center">
                                <span class="small">Total Hari Diajukan:</span>
                                <strong class="h5 mb-0"><span id="jmlHari">0</span> Hari</strong>
                                <input type="hidden" name="jml_hari" id="jml_hari_input" value="0">
                            </div>

                            <div class="form-group mt-2">
                                <label class="form-label small text-muted">Jenis Cuti</label>
                                <select name="jenis_cuti" id="jenis_cuti" class="form-control">
                                    <option value="">Pilih Jenis Cuti</option>
                                    @foreach ($leaves as $d)
                                        @php
                                            $isMelahirkan = str_contains(strtolower($d->name), 'melahirkan');
                                            $isLakiLaki =
                                                strtolower($employee->gender) == 'laki-laki' ||
                                                strtolower($employee->gender) == 'l';
                                        @endphp

                                        {{-- Sembunyikan Cuti Melahirkan jika Laki-laki --}}
                                        @if ($isLakiLaki && $isMelahirkan)
                                            @continue
                                        @endif

                                        <option value="{{ $d->id }}" data-kuota="{{ $d->quota }}">
                                            {{ $d->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mt-2 mb-3">
                                <label class="form-label small text-muted">Keterangan</label>
                                <textarea name="ket" id="ket" cols="30" rows="3" class="form-control" placeholder="Alasan cuti..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 shadow-sm" id="kirim">
                                <ion-icon name="send-outline" class="me-1"></ion-icon> Kirim Pengajuan
                            </button>
                        @else
                            <div class="card border-0 shadow-sm mt-2">
                                <div class="card-body text-center py-5">
                                    <ion-icon name="lock-closed-outline" class="text-warning"
                                        style="font-size: 4rem;"></ion-icon>
                                    <h5 class="mt-3 fw-bold">Fitur Cuti Terkunci</h5>
                                    <p class="text-muted small">
                                        Sesuai kebijakan perusahaan, pengajuan cuti hanya dapat dilakukan setelah minimal 1
                                        tahun masa kerja.<br><br>
                                        Hak cuti Anda akan aktif pada:<br>
                                        <span class="badge bg-light text-primary p-2" style="font-size: 0.9rem;">
                                            {{ $tgl_masuk ? $tgl_masuk->copy()->addYear()->format('d M Y') : '-' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endif

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
            const holidays = {!! json_encode($holidays) !!};

            flatpickr("#tgl_izin", {
                mode: "range",
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr, instance) {
                    let workDays = 0;

                    if (selectedDates.length === 2) {
                        let start = new Date(selectedDates[0]);
                        let end = new Date(selectedDates[1]);
                        let cur = new Date(start);

                        while (cur <= end) {
                            // 1. Ambil data hari (0-6) berdasarkan waktu lokal, bukan UTC
                            let dayOfWeek = cur.getDay();

                            // 2. Format tanggal ke YYYY-MM-DD secara manual (Waktu Lokal)
                            let year = cur.getFullYear();
                            let month = ("0" + (cur.getMonth() + 1)).slice(-2);
                            let day = ("0" + cur.getDate()).slice(-2);
                            let formattedDate = `${year}-${month}-${day}`;

                            // 3. Logika pengecekan
                            // Bukan Minggu (0), Bukan Sabtu (6), dan tidak ada di daftar hari libur
                            if (dayOfWeek !== 0 && dayOfWeek !== 6 && !holidays.includes(formattedDate)) {
                                workDays++;
                            }

                            // 4. Tambah 1 hari secara presisi
                            cur.setDate(cur.getDate() + 1);
                        }
                    } else if (selectedDates.length === 1) {
                        let dayOfWeek = selectedDates[0].getDay();
                        let y = selectedDates[0].getFullYear();
                        let m = ("0" + (selectedDates[0].getMonth() + 1)).slice(-2);
                        let d = ("0" + selectedDates[0].getDate()).slice(-2);
                        let formattedDate = `${y}-${m}-${d}`;

                        if (dayOfWeek !== 0 && dayOfWeek !== 6 && !holidays.includes(formattedDate)) {
                            workDays = 1;
                        }
                    }

                    // Update Tampilan & Input Hidden
                    $("#jmlHari").text(workDays);
                    $("#jml_hari_input").val(workDays);

                    // Validasi warna teks jika melebihi sisa cuti (opsional tapi bagus untuk UX)
                    let sisaCuti = parseInt($("#jmlCuti").text());
                    if (workDays > sisaCuti) {
                        $("#jmlHari").addClass("text-danger").removeClass("text-dark");
                    } else {
                        $("#jmlHari").addClass("text-dark").removeClass("text-danger");
                    }
                },
                // onClose tetap sama seperti kode Anda...
            });
        });


        $('#frmCuti').submit(function(e) {
            var tgl_izin = $('#tgl_izin').val();
            let status = $('#status').val();
            var keterangan = $('#ket').val();
            var jenis_cuti = $('#jenis_cuti').val();
            if (tgl_izin == "") {
                Swal.fire({
                    title: 'Oops',
                    text: 'Tanggal Tidak Boleh Kosong',
                    icon: 'warning',
                    // confirmButtonText: 'OK'
                })
                return false;
            } else if (jenis_cuti == "") {
                Swal.fire({
                    title: 'Oops',
                    text: 'Jenis Cuti Tidak Boleh Kosong',
                    icon: 'warning',
                    // confirmButtonText: 'OK'
                })
                return false;
            } else if (keterangan == "") {
                Swal.fire({
                    title: 'Oops',
                    text: 'Keterangan Tidak Boleh Kosong',
                    icon: 'warning',
                    // confirmButtonText: 'OK'
                })
                return false;
            };
        })
    </script>
@endpush
