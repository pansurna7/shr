@extends('frontend.layout.app')
@section('title', 'Riwayat Presensi')
@push('css')
@endpush<style>
    /* 1. Header Ultra Slim 35px */
    .appHeader {
        height: 35px !important;
        min-height: 35px !important;
        padding: 0 !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1001;
        background-color: #1E74FD !important;
    }

    /* Paksa Tombol Back & Judul ke posisi 35px */
    .appHeader .left,
    .appHeader .left .headerButton {
        height: 35px !important;
        min-height: 35px !important;
        display: flex !important;
        align-items: center !important;
    }

    .appHeader .pageTitle {
        line-height: 35px !important;
        height: 35px !important;
        font-size: 14px !important;
        font-weight: 600;
        position: absolute;
        width: 100%;
        text-align: center;
        left: 0;
        top: 0;
        z-index: -1;
        margin: 0 !important;
    }

    /* 2. PAKSA KONTEN NAIK (Menghilangkan gap) */
    #appCapsule {
        padding-top: 35px !important;
        /* Pas mengikuti tinggi header */
        margin-top: 0 !important;
        background: #f4f7f9;
        min-height: 100vh;
    }

    /* Mengurangi jarak mt-2 bawaan agar lebih rapat */
    .section.mt-2 {
        margin-top: 8px !important;
    }

    .card {
        border-radius: 15px !important;
        border: none !important;
    }

    .custom-select {
        border-radius: 10px !important;
        background-color: #f8fafc !important;
        border: 1px solid #edf2f7 !important;
        height: 38px !important;
        font-size: 13px !important;
    }

    .label {
        font-size: 11px !important;
        font-weight: 600;
        color: #95a5a6;
        margin-bottom: 2px !important;
    }
</style>
@section('header')
    <div class="appHeader bg-primary text-light"
        style="height: 35px !important; min-height: 35px !important; border: none !important; display: flex !important; align-items: center !important; position: fixed; top: 0; width: 100%; z-index: 1001; padding: 0 !important;">
        <div class="left" style="height: 35px !important; display: flex; align-items: center;">
            <a href="{{ route('frontend.dashboards') }}" class="headerButton"
                style="min-height: 35px !important; height: 35px !important; display: flex !important; align-items: center !important; padding: 0 8px !important; margin: 0 !important;">
                <ion-icon name="chevron-back-outline"
                    style="font-size: 18px !important; line-height: 35px !important;"></ion-icon>
            </a>
        </div>
        <div class="pageTitle"
            style="line-height: 35px !important; height: 35px !important; font-size: 13px !important; font-weight: 600; margin: 0 !important; position: absolute; width: 100%; text-align: center; left: 0; top: 0; z-index: -1;">
            History Presensi
        </div>
    </div>
@endsection

@section('content')
    <div id="appCapsule" style="padding-top: 35px !important;">

        <div class="section" style="margin-top: -20px !important; position: relative; z-index: 10;">
            <div class="card shadow-sm border-0" style="border-radius: 15px !important;">
                <div class="card-body" style="padding: 12px 15px !important;">
                    <div class="row">
                        <div class="col-6 pr-1">
                            <div class="form-group basic p-0 m-0">
                                <label class="label"
                                    style="font-size: 10px; color: #95a5a6; font-weight: 600; margin-bottom: 2px;">BULAN</label>
                                <select name="mont" id="mont" class="form-control"
                                    style="height: 35px !important; font-size: 13px !important; border-radius: 10px !important; background: #f8fafc !important; border: 1px solid #edf2f7 !important;">
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>
                                            {{ $nama_bulan[$i] }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-6 pl-1">
                            <div class="form-group basic p-0 m-0">
                                <label class="label"
                                    style="font-size: 10px; color: #95a5a6; font-weight: 600; margin-bottom: 2px;">TAHUN</label>
                                <select name="tahun" id="year" class="form-control"
                                    style="height: 35px !important; font-size: 13px !important; border-radius: 10px !important; background: #f8fafc !important; border: 1px solid #edf2f7 !important;">
                                    @php
                                        $tahun_mulai = 2022;
                                        $tahun_sekarang = date('Y');
                                    @endphp
                                    @for ($tahun = $tahun_mulai; $tahun <= $tahun_sekarang; $tahun++)
                                        <option value="{{ $tahun }}"
                                            {{ $tahun_sekarang == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary btn-block mt-2 shadow-sm" id="cari"
                        style="border-radius: 12px; height: 40px; font-weight: 700; font-size: 12px; background: #1E74FD !important; border: none;">
                        <ion-icon name="search-outline"
                            style="font-size: 18px; margin-right: 5px; vertical-align: middle;"></ion-icon> TAMPILKAN
                        RIWAYAT
                    </button>
                </div>
            </div>
        </div>

        <div class="section mt-1 mb-5" style="padding: 0 15px !important;">
            <div id="showHistory">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Load otomatis bulan berjalan saat halaman dibuka
            $('#cari').click();
        });

        $('#cari').on('click', function(e) {
            var bulan = $('#mont').val();
            var tahun = $('#year').val();

            $('#showHistory').html(
                '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></div>'
            );

            $.ajax({
                type: "POST",
                url: "/gethistory",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(response) {
                    $('#showHistory').html(response)
                }
            });
        });
    </script>
@endpush
