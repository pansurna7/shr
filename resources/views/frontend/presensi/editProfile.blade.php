@extends('frontend.layout.app')
@section('title','Edit Profile')
@push('css')
{{-- <style>
    /* 1. Header Ultra-Slim & Text Posisi Atas */
    .appHeader {
        height: 32px !important; /* Ukuran bar super tipis */
        background-color: #1E74FD !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        position: fixed !important;
        top: 0;
        width: 100%;
        z-index: 999;
    }

    .appHeader .pageTitle {
        font-size: 14px !important;
        font-weight: 700;
        color: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100%;
        text-align: center;
        position: absolute;
        line-height: 32px !important; /* Menyeimbangkan teks di bar 32px */
        z-index: -1;
    }

    /* Menyesuaikan tombol back agar masuk di bar kecil */
    .appHeader .left .headerButton {
        height: 32px !important;
        display: flex;
        align-items: center;
        padding-left: 8px;
        color: #ffffff !important;
    }

    /* 2. Menghapus Jarak Capsule & Avatar agar nempel ke atas */
    #appCapsule {
        padding-top: 32px !important; /* Mengikuti tinggi header baru */
        background: #ffffff !important;
        margin: 0 !important;
    }

    .avatar-section {
        background: #ffffff;
        padding: 8px 0 35px 0 !important; /* Jarak atas hanya 8px agar sangat dekat topbar */
        text-align: center;
    }

    .avatar-container {
        margin-top: 0 !important;
    }

    .avatar-container img {
        width: 80px !important;
        height: 80px !important;
        border-radius: 50% !important;
        border: 2px solid #1E74FD;
        padding: 2px;
        object-fit: cover;
    }

    /* 3. Card Form Modern & Rapat */
    .full-card-section {
        padding: 0 12px !important;
        margin-top: -30px !important; /* Tarik card ke area profil agar tidak ada gap abu-abu */
        position: relative;
        z-index: 10;
        background: transparent;
    }

    .card {
        border-radius: 20px !important;
        border: none !important;
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
    }

    /* Styling Input agar Rapi */
    .form-group.boxed .label {
        font-size: 11px;
        color: #95a5a6;
        margin-bottom: 2px;
        font-weight: 600;
    }

    .form-control {
        background: #f8fafc !important;
        border: 1px solid #edf2f7 !important;
        border-radius: 8px !important;
        height: 38px !important;
        font-size: 13px;
    }
</style> --}}
@endpush

@section('header')
    <div class="appHeader bg-primary text-light" style="height: 35px !important; min-height: 35px !important; padding: 0 !important; border: none !important; position: fixed; top: 0; width: 100%; z-index: 999; display: flex !important; align-items: center !important;">
        <div class="left" style="height: 35px !important; display: flex; align-items: center;">
            <a href="/frontend/dashboards" class="headerButton" style="min-height: 35px !important; display: flex; align-items: center; padding: 0 10px;">
                <ion-icon name="chevron-back-outline" style="font-size: 20px !important;"></ion-icon>
            </a>
        </div>
        <div class="pageTitle" style="line-height: 35px !important; height: 35px !important; font-size: 14px !important; font-weight: 600; margin: 0; position: absolute; width: 100%; text-align: center; left: 0; z-index: -1;">
            Edit Profile
        </div>
    </div>
@endsection

@section('content')
<div id="appCapsule" style="padding-top: 35px !important; background: #f4f7f9; min-height: 100vh; overflow: visible !important;">

    <div class="avatar-section" style="background: #fff; padding: 15px 0 45px 0 !important; text-align: center;">
        <div class="avatar-container" style="display: inline-block;">
            @php
                $avatar = !empty($employee->avatar) ? asset('storage/' . $employee->avatar) : asset('assets/img/sample/avatar/avatar1.jpg');
            @endphp
            <img src="{{ $avatar }}" alt="avatar" style="width: 85px !important; height: 85px !important; border-radius: 50%; border: 2px solid #1E74FD; padding: 2px; object-fit: cover;">
        </div>
        <h4 style="font-weight:700; font-size:15px; margin: 8px 0 0 0 !important;">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
        <small class="text-muted" style="font-size: 11px;">ID: {{ $employee->nik }}</small>
    </div>

    <div class="section full-card-section" style="padding: 0 12px !important; margin-top: -35px !important; padding-bottom: 120px !important;">
        <div class="card shadow-sm border-0" style="border-radius: 20px !important;">
            <div class="card-body" style="padding: 15px !important;">
                <form action="{{route('update.profile',$employee->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group boxed">
                        <label class="label" style="font-size: 11px; color: #999;">NIK (Non-Editable)</label>
                        <input type="text" class="form-control" value="{{ $employee->nik }}" name="nik" readonly style="background:#f1f3f5 !important; height: 38px !important;">
                    </div>

                    <div class="form-group boxed mt-1">
                        <label class="label" style="font-size: 11px; color: #999;">First Name</label>
                        <input type="text" class="form-control" value="{{ $employee->first_name }}" name="first_name" required style="height: 38px !important;">
                    </div>

                    <div class="form-group boxed mt-1">
                        <label class="label" style="font-size: 11px; color: #999;">Last Name</label>
                        <input type="text" class="form-control" value="{{ $employee->last_name }}" name="last_name" style="height: 38px !important;">
                    </div>

                    <div class="form-group boxed mt-1">
                        <label class="label" style="font-size: 11px; color: #999;">No. WhatsApp / HP</label>
                        <input type="text" class="form-control" value="{{ $employee->mobile }}" name="mobile" style="height: 38px !important;">
                    </div>

                    <div class="form-group boxed mt-1">
                        <label class="label" style="font-size: 11px; color: #999;">Alamat Lengkap</label>
                        <textarea class="form-control" name="address" rows="2" style="font-size: 13px !important;">{{ $employee->address }}</textarea>
                    </div>

                    <div class="form-group mt-2">
                        <input type="file" name="photo" id="fileuploadInput" accept="image/*" style="display:none;">
                        <label for="fileuploadInput" style="display:flex; align-items:center; background:#f8fafc; padding:8px 12px; border-radius:10px; cursor:pointer; border:1px dashed #1E74FD; margin: 0;">
                            <ion-icon name="camera-outline" style="font-size:18px; color:#1E74FD; margin-right:8px;"></ion-icon>
                            <span style="font-weight:600; color:#4b5563; font-size:12px;">Pilih Foto</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-block shadow" style="border-radius:12px; height:45px; font-weight:700; background: #1E74FD !important; border: none !important;">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
