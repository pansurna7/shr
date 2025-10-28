@extends('frontend.layout.app')
@section('title', 'Dashboard')
@push('css')
    <style>
        .imaged {
            width: 70px; /* Example width */
            height: 70px; /* Example height, must match width for a perfect circle */
            border-radius: 50%;
            object-fit: cover; /* Optional: ensures the image covers the entire circular area without distortion */
        }
    </style>
@endpush
@section('content')
    <div class="section" id="user-section">
        <div id="user-detail">
            <div class="avatar">
                <img src="{{asset('storage/' .Auth::user()->avatar)}}" alt="avatar" class="imaged">
            </div>
            <div id="user-info">
                {{-- <h2>{{\Carbon\Carbon::now()->format('H:i:s')}}</h2> --}}
                <h2 id="user-name">{{Auth::user()->full_name}}</h2>
                <span id="user-role">{{Auth::user()->position}}</span>

            </div>
        </div>
    </div>

    <div class="section" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="{{route('edit.profile')}}" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="{{route(('presensi.izin'))}}" class="danger" style="font-size: 40px;">
                                <ion-icon name="calendar-number"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Cuti</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="{{route('presensi.history')}}" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="orange" style="font-size: 40px;">
                                <ion-icon name="location"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Lokasi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensi_today != null)
                                        @php
                                            $path_image = Storage :: url('absensi/'.$presensi_today->photo_in)
                                        @endphp
                                        <img src="{{ url($path_image) }}" alt="" class="imaged w64">
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{$presensi_today != null ? \Carbon\Carbon::parse($presensi_today->time_in)->format('H:i'): "Belum Absen"}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    @if ($presensi_today != null && $presensi_today->photo_out != null)
                                        @php
                                            $path_image = Storage :: url('absensi/'.$presensi_today->photo_out)
                                        @endphp
                                        <img src="{{ url($path_image) }}" alt="" class="imaged w64">
                                    @else
                                        <ion-icon name="camera"></ion-icon>
                                    @endif
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{$presensi_today != null && $presensi_today->time_out != null ? \Carbon\Carbon::parse($presensi_today->time_out)->format('H:i') : 'Belum Absen'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="rekappresence">
            <h4 class="text-center">Rekap Presensi Bulan {{$nama_bulan[$month]}} Tahun {{$year}}</h4>
                <div class="row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important;line-height:0.8rem">
                                <span class="badge bg-danger" style="position: absolute;top:3px;right:5px;font-size:0.6rem;z-index:999;">{{ $rekap_presensi->jml_hadir }}</span>
                                <ion-icon name="accessibility-outline" style="font-size: 1.5rem" class="text-primary mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500; ">Hadir</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important;line-height:0.8rem;">
                                <span class="badge bg-danger" style="position: absolute;top:3px;right:5px;font-size:0.6rem;z-index:999;">{{$rekap_izin->jml_izin}}</span>
                                <ion-icon name="newspaper-outline" style="font-size: 1.5rem" class="text-success mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500; ">Izin</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important;line-height:0.8rem">
                                <span class="badge bg-danger" style="position: absolute;top:3px;right:5px;font-size:0.6rem;z-index:999;">{{$rekap_izin->jml_sakit}}</span>
                                <ion-icon name="medkit-outline" style="font-size: 1.5rem" class="text-warning mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500; ">Sakit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important;line-height:0.8rem">
                                <span class="badge bg-danger" style="position: absolute;top:3px;right:5px;font-size:0.6rem;z-index:999;">{{ $rekap_presensi->jml_telat }}</span>
                                <ion-icon name="alarm-outline" style="font-size: 1.5rem" class="text-danger mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 0.8rem; font-weight:500; ">Telat</span>
                            </div>
                        </div>
                    </div>

                </div>
        </div>

        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            Bulan Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Leaderboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:100px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <ul class="listview image-listview">
                        @foreach ($history_on_mount as $item )
                            <li>
                                <div class="item">
                                    <div class="icon-box bg-primary">
                                        <ion-icon name="finger-print-outline"></ion-icon>
                                    </div>
                                    <div class="in">
                                        <div>{{ date("d-m-Y",strtotime($item->date)) }}</div>
                                        <span class="badge badge-success">{{date("H:i:s",strtotime($item->time_in))}}</span>
                                        <span class="badge badge-danger">{{$item->time_out != Null ? \Carbon\carbon::parse($item->time_out)->format('H:i:s') : 'Belum Absen'}}</span>
                                    </div>

                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <ul class="listview image-listview">
                        @foreach ($leader_board as $data )
                            <li>
                                <div class="item">
                                    <img src="{{asset('storage/' .$data->avatar)}}" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                            <b>{{ $data->name }}</b><br>
                                            <small class="text-muted">{{ $data->position }}</small>
                                        </div>
                                        <span class="badge {{ $data->time_in != null || $data->date != null ? "bg-success" : "bg-danger"}}">{{$data->time_in != null ? "Hadir" : "Absen"}}</span>

                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection
