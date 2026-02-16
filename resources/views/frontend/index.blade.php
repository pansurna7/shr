@extends('frontend.layout.app')
@section('title', 'Dashboard')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
<style>
    /* Menghilangkan border default slider agar bersih */
    .splide__track { padding: 5px 0; }
    .splide__pagination { bottom: -15px; }
    .splide__pagination__page.is-active { background: #1e3a8a; }
    #user-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        /* Padding bawah disesuaikan karena card menu sudah tidak ada */
        padding: 45px 20px 60px 20px;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
        position: relative;
        color: white;
        z-index: 1;
    }
    .avatar-img {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.4);
        object-fit: cover;
        flex-shrink: 0;
    }
    .ms-3 {
        margin-left: 1rem !important;
        max-width: 70%;
    }
    .avatar-wrapper {
        flex-shrink: 0;
    }
    .bg-opacity-20 {
        background-color: rgba(255, 255, 255, 0.2) !important;
        backdrop-filter: blur(4px);
    }
    .logout { position: absolute; top: 40px; right: 20px; font-size: 25px; color: white; }

    /* Rekap Styling ditingkatkan karena sekarang menjadi fokus utama */
    .rekap-section {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: -30px; /* Membuat rekap sedikit naik menimpa area biru */
        z-index: 2;
        position: relative;
    }
    .rekap-item {
        flex: 1;
        background: #fff;
        padding: 12px 5px;
        border-radius: 18px;
        text-align: center;
        position: relative;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
    }
    .rekap-item .count {
        position: absolute;
        top: -8px;
        right: -5px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: bold;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }
    .rekap-item ion-icon {
        font-size: 22px;
        margin-bottom: 2px;
    }
    .rekap-item span {
        font-size: 10px;
        color: #64748b;
        display: block;
        font-weight: 600;
    }

    /* Announcement & Presence */
    .announcement-box {
        background: #fff9db;
        border-left: 5px solid #fcc419;
        border-radius: 15px;
    }
    .announcement-list {
        max-height: 250px; /* Batasi tinggi maksimal */
        overflow-y: auto;  /* Aktifkan scroll vertikal */
        scrollbar-width: none; /* Sembunyikan scrollbar di Firefox */
    }
    .announcement-list::-webkit-scrollbar {
        display: none; /* Sembunyikan scrollbar di Chrome/Safari */
    }
    .presence-img {
        width: 45px; height: 45px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid white;
        margin-bottom: 5px;
    }
    .rounded-xl { border-radius: 20px !important; }

</style>
@endpush

@section('content')
<div class="section" id="user-section">
    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">@csrf</form>
    <a class="logout" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <ion-icon name="log-out-outline"></ion-icon>
    </a>
    <div class="d-flex align-items-center">
    <div class="avatar-wrapper">
        <img src="{{ Auth::user()->employee->avatar ? asset('storage/'.Auth::user()->employee->avatar) : asset('assets/img/avatar.png') }}"
             class="avatar-img shadow-sm">
    </div>

    <div class="ms-3 d-flex flex-column justify-content-center">
        <h3 class="m-0 fw-bold text-white" style="font-size: 1.1rem; line-height: 1.2; letter-spacing: 0.3px;">
            {{ Auth::user()->employee->first_name .' ' . Auth::user()->employee->last_name }}
        </h3>

        <div class="text-white-50 fw-medium" style="font-size: 0.85rem; line-height: 1.4;">
            {{ Auth::user()->employee->position->name ?? 'Staff' }}
        </div>

        <div class="mt-1">
            <span class="badge bg-white bg-opacity-20 text-white fw-light shadow-none d-inline-flex align-items-center"
                  style="font-size: 0.7rem; padding: 4px 10px; border-radius: 12px; backdrop-filter: blur(4px);">
                <ion-icon name="location-outline" class="me-1"></ion-icon>
                {{ Auth::user()->employee->branch->name ?? 'Pusat' }}
            </span>
        </div>
    </div>
</div>
</div>



<div class="section mt-2 px-2">
    <div class="rekap-section">
        <div class="rekap-item shadow-sm">
            <span class="count" style="background: #10b981;">{{ $rekap_presensi ?? 0 }}</span>
            <ion-icon name="checkmark-circle" class="text-success"></ion-icon>
            <span>Hadir</span>
        </div>
        <div class="rekap-item shadow-sm">
            <span class="count" style="background: #3b82f6;">{{ $rekap_izin->jml_izin ?? 0 }}</span>
            <ion-icon name="document-text" class="text-primary"></ion-icon>
            <span>Izin</span>
        </div>
        <div class="rekap-item shadow-sm">
            <span class="count" style="background: #f59e0b;">{{ $rekap_izin->jml_sakit ?? 0 }}</span>
            <ion-icon name="medkit" class="text-warning"></ion-icon>
            <span>Sakit</span>
        </div>
        <div class="rekap-item shadow-sm">
            <span class="count" style="background: #ef4444;">{{ $rekap_izin->jml_cuti ?? 0 }}</span>
            <ion-icon name="calendar-clear" class="text-danger"></ion-icon>
            <span>Cuti</span>
        </div>
    </div>
</div>


<div class="section mt-2 px-2">
    <div class="row g-2">
        <div class="col-6">
            <div class="card bg-success text-white presence-card py-3 shadow-sm">
                <div class="card-body p-0 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="presence-img-container d-flex align-items-center justify-content-center mb-1" style="width: 50px; height: 50px;">
                            @if($presensi_today && $presensi_today->photo_in)
                                <img src="{{ asset('storage/absensi/'.$presensi_today->photo_in) }}" class="presence-img m-0">
                            @else
                                <ion-icon name="camera-outline" style="font-size: 32px;"></ion-icon>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-center mt-1" style="gap: 4px;">
                            <small class="opacity-75" style="font-size: 11px;">Masuk:</small>
                            <span class="fw-bold" style="font-size: 15px;">
                                {{ $presensi_today ? date('H:i', strtotime($presensi_today->time_in)) : '--:--' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card bg-danger text-white presence-card py-3 shadow-sm">
                <div class="card-body p-0 text-center">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                        <div class="presence-img-container d-flex align-items-center justify-content-center mb-1" style="width: 50px; height: 50px;">
                            @if($presensi_today && $presensi_today->photo_out)
                                <img src="{{ asset('storage/absensi/'.$presensi_today->photo_out) }}" class="presence-img m-0">
                            @else
                                <ion-icon name="camera-outline" style="font-size: 32px;"></ion-icon>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-center mt-1" style="gap: 4px;">
                            <small class="opacity-75" style="font-size: 11px;">Pulang:</small>
                            <span class="fw-bold" style="font-size: 15px;">
                                {{ ($presensi_today && $presensi_today->time_out) ? date('H:i', strtotime($presensi_today->time_out)) : '--:--' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section mt-3 px-2">
    <div id="announcement-slider" class="splide">
        <div class="splide__track">
            <div class="splide__list">
                @forelse($announcements as $info)
                <div class="splide__slide">
                    <div class="card announcement-box border-0 shadow-sm mx-1">
                        <div class="card-body p-2">
                            <div class="d-flex">
                                <ion-icon name="megaphone" class="text-warning me-2" style="font-size: 20px;"></ion-icon>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-bold">{{ $info->title }}</small>
                                        <small class="text-muted" style="font-size: 9px;">{{ date('d M', strtotime($info->created_at)) }}</small>
                                    </div>

                                    <p class="m-0" style="font-size: 11px; color: #555; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $info->content }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <a href="javascript:void(0);"
   class="text-primary fw-bold btn-read-more"
   style="font-size: 10px;"
   data-toggle="modal"
   data-target="#modalAnnouncement"
   data-title="{{ $info->title }}"
   data-content="{{ $info->content }}">
   Read More...
</a>
                                        @if($info->file_name)
                                        <a href="{{ route('download.announcement', $info->file_name) }}" class="btn btn-sm btn-primary rounded-pill" style="font-size: 9px; padding: 2px 8px;">
                                            <ion-icon name="download-outline"></ion-icon> Download
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAnnouncement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalBody" style="font-size: 13px; color: #555; line-height: 1.6;"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<div class="section mt-3 px-2 mb-5">
    <ul class="nav nav-tabs style1" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#history">Histori</a></li>
        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#leader">Leaderboard</a></li>
    </ul>
    <div class="tab-content mt-2">
        <div class="tab-pane fade show active" id="history">
    @forelse($history_on_mount as $item)
    <div class="card mb-1 border-0 shadow-sm rounded-lg p-2">
        <div class="d-flex align-items-center">
            <ion-icon name="finger-print" class="text-success h2 me-2"></ion-icon>
            <div class="w-100">
                <div class="d-flex justify-content-between">
                    <small class="fw-bold text-dark">{{ date('d M Y', strtotime($item->date)) }}</small>
                    <small class="text-primary fw-bold">{{ $item->name ?? 'Non-Shift' }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size: 12px;" class="text-muted">
                        {{ date('H:i', strtotime($item->time_in)) }} -
                        {{ $item->time_out ? date('H:i', strtotime($item->time_out)) : '--:--' }}
                    </span>

                    @php
                        // Jam ketika karyawan melakukan absen
                        $jam_absen = date('H:i', strtotime($item->time_in));

                        // Batas akhir absen (entry_time) dari database
                        // Kita format ke H:i agar perbandingannya apel-ke-apel
                        $batas_masuk = $item->entry_time ? date('H:i', strtotime($item->entry_time)) : null;
                    @endphp

                    @if($batas_masuk)
                        @if($jam_absen > $batas_masuk)
                            <span class="badge bg-danger" style="font-size: 9px;">Terlambat</span>
                        @else
                            <span class="badge bg-success" style="font-size: 9px;">Ontime</span>
                        @endif
                    @else
                        {{-- Muncul jika join jadwal gagal atau entry_time kosong --}}
                        <span class="badge bg-secondary" style="font-size: 9px;">No Schedule</span>
                    @endif
                </div>
                {{-- Opsi: Tampilkan jam batas untuk memastikan --}}
                @if($batas_masuk)
                    <div style="font-size: 8px; color: #bbb;">Batas Masuk: {{ $batas_masuk }}</div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center p-3"><small class="text-muted">Tidak ada data bulan ini.</small></div>
    @endforelse
</div>

        <div class="tab-pane fade" id="leader">
            <ul class="listview image-listview shadow-sm rounded-xl overflow-hidden">
                @foreach($leader_board as $l)
                <li>
                    <div class="item">
                        <img src="{{ $l->avatar ? asset('storage/'.$l->avatar) : asset('assets/img/avatar.png') }}" class="image">
                        <div class="in">
                            <div>
                                <div class="fw-bold" style="font-size: 13px;">{{ $l->first_name }}</div>
                                <small class="text-muted">{{ $l->position_name }}</small>
                            </div>
                            <span class="badge {{ $l->time_in ? 'bg-success' : 'bg-secondary' }}">
                                {{ $l->time_in ? date('H:i', strtotime($l->time_in)) : 'Absen' }}
                            </span>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.querySelectorAll('.splide__slide').length > 0) {
            new Splide('#announcement-slider', {
                type   : 'loop',
                drag   : 'free',
                snap   : true,
                perPage: 1,
                arrows : false, // Sembunyikan panah untuk tampilan mobile cleaner
                autoplay: true,
                interval: 4000,
                pagination: true,
            }).mount();
        }
    });

    $(document).ready(function() {
    // Saat modal akan ditampilkan
    $('#modalAnnouncement').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Tombol yang diklik
        var title = button.data('title');    // Ambil data-title
        var content = button.data('content'); // Ambil data-content

        var modal = $(this);
        modal.find('#modalTitle').text(title);
        modal.find('#modalBody').text(content);
    });
});
</script>

@endpush
