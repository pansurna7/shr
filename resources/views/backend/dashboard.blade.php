@extends('backend.layouts.app')
@section('title','Dashboard')
@section('content')

    <div class="row">
        <div class="col">
            <div class="card radius-10 overflow-hidden">
                <div class="card-body">
                    <div class="text-white font-35"><i class='bx bx-group'></i></div>
                    <h3 class="mb-0 mt-0">{{$employee}}</h3>
                    <p class="mb-0">Karyawan</p>
                </div>
                <div id="emp-nps"></div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 overflow-hidden">
                <div class="card-body">
                    <div class="text-white font-35"><i class='bx bx-fingerprint'></i></div>
                    <h3 class="mb-0 mt-0">{{$rekap_presensi->jml_hadir}}</h3>
                    <p class="mb-0">Karyawan Hadir</p>
                </div>
               <div id="training-expenses"></div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 overflow-hidden">
                <div class="card-body">
                    <div class="text-white font-35"><i class='bx bx-edit'></i></div>
                    <h3 class="mb-0 mt-0">{{$rekap_izin->jml_izin}}</h3>
                    <p class="mb-0">Karyawan Izin</p>
                </div>
                <div id="csr-activities"></div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 overflow-hidden">
                <div class="card-body">
                    <div class="text-white font-35"><i class='bx bx-sad'></i></div>
                    <h3 class="mb-0 mt-0">{{$rekap_izin->jml_sakit}}</h3>
                    <p class="mb-0">Karyawan Sakit</p>
                </div>
                <div id="starter-this-month"></div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 overflow-hidden">
                <div class="card-body">
                    <div class="text-white font-35"><i class='bx bx-time'></i></div>
                    <h3 class="mb-0 mt-0">{{$rekap_presensi->jml_telat ?? 0 }}</h3>
                    <p class="mb-0">Karyawan Terlambat</p>
                </div>
                <div id="emp-late"></div>
            </div>
        </div>
    </div>
@endsection
