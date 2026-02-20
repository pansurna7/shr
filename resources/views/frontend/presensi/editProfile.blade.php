@extends('frontend.layout.app')
@section('title','Edit Profile')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/frontend/dashboards" class="headerButton">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle mt-2">Edit Profile</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
<div id="appCapsule">
    <div class="section mt-3 text-center">
        <div class="avatar-section">
            @if(!empty($employee->avatar))
                <img src="{{asset('storage/' .Auth::user()->employee->avatar)}}" alt="avatar" class="imaged w100 rounded shadow">
            @else
                <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="avatar" class="imaged w100 rounded shadow">
            @endif
            <div class="button-wrapper">
                <h4 class="mt-1 mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h4>
                <span class="text-muted">ID: {{ $employee->nik }}</span>
            </div>
        </div>
    </div>

    <div class="section mt-2 mb-5">
        @if (Session::get('success'))
            <div class="alert alert-success mb-2 shadow-sm rounded">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger mb-2 shadow-sm rounded">
                {{ Session::get('error') }}
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{route('update.profile',$employee->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">NIK (Non-Editable)</label>
                            <input type="text" class="form-control bg-light" value="{{ $employee->nik }}" name="nik" readonly>
                            <i class="clear-input">
                                <ion-icon name="lock-closed-outline"></ion-icon>
                            </i>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">First Name</label>
                            <input type="text" class="form-control" value="{{ $employee->first_name }}" name="first_name" placeholder="Masukkan nama depan" required>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">Last Name</label>
                            <input type="text" class="form-control" value="{{ $employee->last_name }}" name="last_name" placeholder="Masukkan nama belakang">
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">No. WhatsApp / HP</label>
                            <input type="text" class="form-control" value="{{ $employee->mobile }}" name="mobile" placeholder="Contoh: 0812xxxx">
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ $employee->address }}</textarea>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="label">Password Baru</label>
                            <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin ganti">
                            <small class="text-info">*Min. 8 Karakter</small>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="label">Foto Profil</label>
                        <div class="custom-file-upload mt-1" id="fileUpload1">
                            <input type="file" name="photo" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                            <label for="fileuploadInput">
                                <span>
                                    <strong>
                                        <ion-icon name="cloud-upload-outline"></ion-icon>
                                        <i>Tap untuk ganti foto</i>
                                    </strong>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary btn-block btn-lg shadow">
                            <ion-icon name="save-outline"></ion-icon>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Tambahan CSS agar lebih cakep */
    .avatar-section {
        padding: 20px 0;
        background: #fff;
    }
    .card {
        border-radius: 15px !important;
    }
    .form-control {
        border-bottom: 1px solid #E1E1E1 !important;
        padding-left: 0 !important;
    }
    .form-group.boxed .label {
        font-weight: 600;
        color: #555;
        margin-bottom: 2px;
    }
    .custom-file-upload label {
        border: 2px dashed #D2D2D2 !important;
        border-radius: 10px !important;
        background: #f9f9f9;
    }
    .bg-light {
        background-color: #f8f9fa !important;
        color: #888;
    }
</style>
@endsection
