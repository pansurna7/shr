@extends('frontend.layout.app')
@section('title','Pengajuan Izin/Sakit/Presensi')
@section('header')
    <style>
        /* Header Ultra Slim */
        .appHeader {
            height: 35px !important;
            min-height: 35px !important;
            padding: 0 !important;
            border: none !important;
            display: flex !important;
            align-items: center !important; /* Memastikan semua elemen di tengah secara vertikal */
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1001;
        }

        /* Menarik tombol back ke atas */
        .appHeader .left {
            height: 35px !important;
            display: flex;
            align-items: center;
        }

        .appHeader .left .headerButton {
            min-height: 35px !important;
            height: 35px !important;
            display: flex !important;
            align-items: center !important;
            padding: 0 10px !important;
            margin: 0 !important; /* Hilangkan margin jika ada */
        }

        .appHeader .left .headerButton ion-icon {
            font-size: 20px !important;
            line-height: 35px !important; /* Paksa icon sejajar teks */
        }

        .appHeader .pageTitle {
            line-height: 35px !important; /* Samakan dengan tinggi header agar teks naik */
            height: 35px !important;
            font-size: 14px !important;
            font-weight: 600;
            position: absolute;
            width: 100%;
            text-align: center;
            left: 0;
            top: 0;
            z-index: -1;
        }

        .fixed-filter {
            position: fixed;
            top: 35px !important;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 10px 15px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
    </style>

    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{route('frontend.dashboards')}}" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">@yield('title')</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="fixed-filter">
        <form method="GET" action="/presensi/izin" id="formFilter">
            <div class="row">
                <div class="col-7 pr-1">
                    <select name="bulan" id="bulan" class="form-control border-0 bg-light" style="border-radius: 10px; height: 38px; font-size: 13px;" onchange="this.form.submit()">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan', date('n')) == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-5 pl-1">
                    <select name="tahun" id="tahun" class="form-control border-0 bg-light" style="border-radius: 10px; height: 38px; font-size: 13px;" onchange="this.form.submit()">
                        @php $thnSkrg = date('Y'); @endphp
                        @for ($thn = $thnSkrg; $thn >= 2024; $thn--)
                            <option value="{{ $thn }}" {{ request('tahun', date('Y')) == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div id="appCapsule" style="padding-top: 95px !important; padding-bottom: 100px !important;">
        <div class="section">
            @foreach ($submissions as $d )
                <div class="card">
                    <div class="card-body" style="padding: 12px;">
                        <div class="historycontent">
                            <div class="iconpresensi">
                                @if ($d->condition == 1)
                                    <ion-icon name="document-outline" style="font-size: 28px; color:#0d6efd"></ion-icon>
                                @elseif ($d->condition == 2 || $d->condition == 3)
                                    <ion-icon name="medkit-outline" style="font-size: 28px; color:#b53b30"></ion-icon>
                                @elseif ($d->condition == 4)
                                    <ion-icon name="calendar-outline" style="font-size: 28px; color:#198754"></ion-icon>
                                @elseif ($d->condition == 5)
                                    <ion-icon name="timer-outline" style="font-size: 28px; color:#e1ae13"></ion-icon>
                                @endif
                            </div>
                            <div class="datapresensi">
                                <h4 style="margin: 0; font-size: 14px; font-weight: 700;">
                                    {{ date('d M Y', strtotime($d->date)) }}
                                </h4>
                                <p style="margin: 0; font-size: 12px; color: #666;">
                                    Submission: <b>
                                        @if($d->condition == "1") Izin
                                        @elseif($d->condition == "2") Sakit
                                        @elseif($d->condition == "3") Sakit (Surat Dokter)
                                        @elseif($d->condition == "5") Presensi
                                        @else Cuti @endif
                                    </b>
                                </p>
                                <small class="text-muted" style="font-style: italic; font-size: 11px;">{{$d->information}}</small>

                                @if($d->photo)
                                    <div class="mt-1">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPhoto{{ $d->id }}">
                                            <img src="{{ asset('storage/' . $d->photo) }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                    </div>
                                    @endif
                            </div>
                            <div class="status">
                                <span class="badge {{ $d->status == 0 ? 'badge-warning' : ($d->status == 1 ? 'badge-success' : 'badge-danger') }}" style="font-size: 10px;">
                                    {{ $d->status == "0" ? "Waiting" : ($d->status == "1" ? "Approved" : "Reject") }}
                                </span>
                                <div style="margin-top: 5px; font-weight: 800; font-size: 12px;">{{$d->total_days}} Hari</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="fab-button animate bottom-right dropdown" style="bottom: 80px;">
        <a href="#" class="fab bg-primary" data-toggle="dropdown">
            <ion-icon name="add-outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('submission.izin')}}"><ion-icon name="document-outline"></ion-icon><p>Izin Absen</p></a>
            <a class="dropdown-item" href="{{route('submission.sakit')}}"><ion-icon name="document-outline"></ion-icon><p>Sakit Absen</p></a>
            <a class="dropdown-item" href="{{route('submission.cuti')}}"><ion-icon name="document-outline"></ion-icon><p>Cuti</p></a>
            <a class="dropdown-item" href="{{route('submission.time')}}"><ion-icon name="time-outline"></ion-icon><p>Jam</p></a>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
