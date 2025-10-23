@extends('frontend.layout.app')
@section('title','Pengajuan Izin/Sakit')
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
    <div class="fab-button bottom-right" style="margin-bottom: 30%">
        <a href="{{route('presensi.pengajuan')}}" class="fab">
            <ion-icon name="add-outline"></ion-icon>
        </a>
    </div>
    <div class="row" style="margin-top: 4rem">
        <div class="col">
            @foreach ($submissions as $d )
                <div class="listview image-listview">
                    <li>
                        <div class="item">
                            <img src="{{ asset( $d->photo ? 'storage/' . $d->photo : 'assets/images/avatars/avatar-1.png' ) }}" alt="image" class="image">
                            <div class="in">
                                <div>
                                    <b>{{ date('d-m-Y',strtotime($d->date)) }}
                                        ({{
                                            $d->condition == "0" ? "Izin"
                                            : ( $d->condition == "1" ? "Sakit" : "Sakit Dokter" )
                                        }})</b><br>
                                    <small class="text-muted">
                                        {{$d->information}}
                                    </small>
                                </div>
                                <div>
                                    <span class="badge {{
                                            $d->status == 0 ? 'badge-warning' : (
                                            $d->status == 1 ? 'badge-success' : 'badge-danger' )
                                        }}">
                                        {{ $d->status == "0"
                                            ? "Waiting"
                                            : ( $d->status == "1" ? "Approved" : "Decline" )
                                        }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </li>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
