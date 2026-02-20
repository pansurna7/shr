@extends('frontend.layout.app')
@section('title','Pengajuan Izin/Sakit/Presensi')
@section('header')
    <style>
            .fl-wrapper {
                position: fixed;
                -webkit-transition: all 1s ease-in-out;
                -moz-transition: all 1s ease-in-out;
                transition: all 1s ease-in-out;
                width: 24em;
                z-index: 10000000
            }

            .historycontent{
                display: flex;
                gap: 1px
            }
            .datapresensi{
                margin-left: 10px;
            }
            .status{
                position: absolute;
                right: 10px;
            }


            /* popup gambar */

            .modal-content {
                border-radius: 20px;
                overflow: hidden;
            }
            .modal-body img {
                max-height: 80vh; /* Agar gambar tidak melebihi tinggi layar hp */
                object-fit: contain;
            }

    </style>
    <div class="appHeader bg-primary text-align">
        <div class="left">
            <a href="{{route('frontend.dashboards')}}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">@yield('title')</div>
        <div class="right"></div>
    </div>
@endsection
@section('content')
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 30%">
        <a href="#" class="fab bg-primary" data-toggle="dropdown">
            <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="image outline" ></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="{{route('submission.izin')}}">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Izin Absen</p>
            </a>
            <a class="dropdown-item" href="{{route('submission.sakit')}}">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Sakit Absen</p>
            </a>
            <a class="dropdown-item" href="{{route('submission.cuti')}}">
                <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                <p>Cuti</p>
            </a>
            <a class="dropdown-item" href="{{route('submission.time')}}">
                <ion-icon name="time-outline"></ion-icon>
                <p>Jam</p>
            </a>
        </div>
    </div>
    <div class="fixed-filter bg-white shadow-sm" style="position: fixed; top: 4.5rem; left: 10px; right: 15px; z-index: 1000; padding: 10px 15px;">
        <form method="GET" action="/presensi/izin" id="formFilter">
            <div class="row">
                <div class="col-7 pr-1">
                    <select name="bulan" id="bulan" class="form-control border-0 bg-light" style="border-radius: 10px;" onchange="this.form.submit()">
                        <option value="" {{ request('bulan') === "" ? 'selected' : '' }}>Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $selected = false;
                                if (request()->has('bulan')) {
                                    if (request('bulan') == $i) $selected = true;
                                } else {
                                    if (date('n') == $i) $selected = true;
                                }
                            @endphp
                            <option value="{{ $i }}" {{ $selected ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-5 pl-1">
                    <select name="tahun" id="tahun" class="form-control border-0 bg-light" style="border-radius: 10px;" onchange="this.form.submit()">
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

    <div style="margin-top: 8rem;"></div>

    <div class="row">
        <div class="col">
            @foreach ($submissions as $d )
                <div class="card mt-1">
                    <div class="card-body">
                        <div class="historycontent">
                            <div class="iconpresensi">
                                @if ($d->condition == 1)
                                    <ion-icon name="document-outline" style="font-size: 30px; color:#0d6efd"></ion-icon>
                                @elseif ($d->condition == 2 || $d->condition == 3)
                                    <ion-icon name="medkit-outline" style="font-size: 30px; color:#b53b30"></ion-icon>
                                @elseif ($d->condition == 4)
                                    <ion-icon name="calendar-outline" style="font-size: 30px; color:#198754"></ion-icon>
                                @elseif ($d->condition == 5)
                                    <ion-icon name="timer-outline" style="font-size: 30px; color:#e1ae13"></ion-icon>
                                @endif
                            </div>
                            <div class="datapresensi">
                                <h4 style="line-height: 10px;">
                                    {{ date('d-m-Y', strtotime($d->date)) }}
                                        @if($d->end_date && $d->end_date != $d->date)
                                            s/d {{ date('d-m-Y', strtotime($d->end_date)) }}
                                        @endif
                                </h4>
                                Submission:

                                    <span class="fw-bold">
                                        @if($d->condition == "1")
                                            Izin
                                        @elseif($d->condition == "2")
                                            Sakit
                                        @elseif($d->condition == "3")
                                            Sakit (Surat Dokter)
                                        @elseif($d->condition == "5")
                                            Presensi
                                        @else
                                            {{-- Jika condition bukan 1, 2, atau 3, maka dianggap Cuti --}}
                                            @if($d->leave_id == 1)
                                                Cuti Tahunan
                                            @elseif($d->leave_id == 2)
                                                Cuti Melahirkan
                                            @else
                                                Cuti
                                            @endif

                                        @endif
                                    </span>

                                <br>

                                <small class="text-muted font-italic ">
                                    {{$d->information}}
                                </small> <br>


                                @if($d->photo)
                                    <div class="mt-2">
                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPhoto{{ $d->id }}">
                                            <img src="{{ asset('storage/' . $d->photo) }}"
                                                class="img-thumbnail shadow-sm"
                                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;"
                                                alt="Lampiran">
                                        </a>
                                    </div>

                                    <div class="modal fade" id="modalPhoto{{ $d->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">Lampiran Dokumen</h5>
                                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center p-0">
                                                    <img src="{{ asset('storage/' . $d->photo) }}" class="img-fluid w-100">
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <a href="{{ asset('storage/' . $d->photo) }}" target="_blank" class="btn btn-primary btn-sm">
                                                        <ion-icon name="download-outline"></ion-icon> Buka Full Size
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <span class="text-muted small"></span>
                                @endif

                            </div>
                            <div class="status">
                                <span class="badge mt-2 {{
                                        $d->status == 0 ? 'badge-warning' : (
                                        $d->status == 1 ? 'badge-success' : 'badge-danger' )
                                    }}">
                                    {{ $d->status == "0"
                                        ? "Waiting"
                                        : ( $d->status == "1" ? "Approved" : "Reject" )
                                    }}
                                </span><br>
                                <p class="text-muted" style="margin-top: 5px; font-weight:bold; text-align:center ">
                                    {{$d->total_days}} Hari
                                </p><br>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
