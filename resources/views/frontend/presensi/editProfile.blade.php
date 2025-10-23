@extends('frontend.layout.app')
@section('title','Edit Profile')
@section('header')
    <div class="appHeader bg-primary text-align">
        <div class="left">
            <a href="/frontend/dashboards" class="headerButton goBack">
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
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>
    <form action="{{route('update.profile',$employee->id)}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control read" value="{{ $employee->nik }}" name="nik" placeholder="Nama Lengkap" autocomplete="off" readonly>
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $employee->full_name }}" name="full_name" placeholder="Nama Lengkap" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $employee->mobile }}" name="mobile" placeholder="No. HP" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="text" class="form-control" value="{{ $employee->address }}" name="address" autocomplete="off">
                </div>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
                </div>
            </div>
            <div class="custom-file-upload" id="fileUpload1">
                <input type="file" name="photo" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                <label for="fileuploadInput">
                    <span>
                        <strong>
                            <ion-icon name="cloud-upload-outline" role="img" class="md hydrated" aria-label="cloud upload outline"></ion-icon>
                            <i>Tap to Upload</i>
                        </strong>
                    </span>
                </label>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <button type="submit" class="btn btn-primary btn-block">
                        <ion-icon name="refresh-outline"></ion-icon>
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection
