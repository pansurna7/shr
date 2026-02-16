@extends('frontend.layout.app')
@section('title','Riwayat Presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{route('frontend.dashboards')}}" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">History Presensi</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div id="appCapsule">
        <div class="section mt-2">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label">Bulan</label>
                                    <select name="mont" id="mont" class="form-control custom-select">
                                        @for ( $i=1; $i <=12; $i++)
                                            <option value="{{$i}}"{{ date('n') == $i ? 'selected': '' }}>{{$nama_bulan[$i]}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label">Tahun</label>
                                    <select name="tahun" id="year" class="form-control custom-select">
                                        @php
                                            $tahun_mulai = 2022;
                                            $tahun_sekarang = date("Y");
                                        @endphp
                                        @for ($tahun = $tahun_mulai; $tahun <= $tahun_sekarang; $tahun++)
                                            <option value="{{$tahun}}" {{$tahun_sekarang == $tahun ? "selected" : "" }}>{{$tahun}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block mt-2 shadow" id="cari">
                        <ion-icon name="search-outline"></ion-icon> Tampilkan Riwayat
                    </button>
                </div>
            </div>
        </div>

        <div class="section mt-2 mb-5">
            <div id="showHistory">
                <div class="text-center p-5 text-muted">
                    <ion-icon name="calendar-outline" style="font-size: 48px; opacity: 0.2;"></ion-icon>
                    <p>Silakan pilih periode</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-select {
            border-bottom: 1px solid #E1E1E1 !important;
            background-color: transparent !important;
        }
        #appCapsule { padding-top: 20px; }
    </style>
@endsection

@push('scripts')
    <script>
        $(function(){
            // Load otomatis bulan berjalan saat halaman dibuka
            $('#cari').click();
        });

        $('#cari').on('click', function(e) {
            var bulan = $('#mont').val();
            var tahun = $('#year').val();

            $('#showHistory').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>');

            $.ajax({
                type: "POST",
                url: "/gethistory",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan : bulan,
                    tahun : tahun
                },
                cache : false,
                success: function (response) {
                    $('#showHistory').html(response)
                }
            });
        });
    </script>
@endpush
