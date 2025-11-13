@extends('backend.layouts.app')
@section('title','Recaps')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Rekap Presensi</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-user-circle"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>

            </nav>

        </div>
    </div>

    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-cog me-1 font-22 text-white"></i>
                            </div>
                            <h5 class="mb-0 text-white">Rekap Presensi</h5>
                        </div>
                        <hr>
                        <form method="POST" action="{{route('rekap.cetak')}}" target="_blank">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-light"> <div class="card-body">
                                            <select name="bulan" id="bulan" class="form-select">
                                                <option value="">Bulan</option>
                                                @for ( $i=1; $i <=12; $i++)
                                                    <option value="{{$i}}"{{ date('m') == $i ? 'selected': '' }}>{{$nama_bulan[$i]}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card bg-light"> <div class="card-body">
                                            <select name="tahun" id="tahun" class="form-select">
                                                <option value="tahun">Tahun</option>
                                                @php
                                                    $tahun_mulai=2022;
                                                    $tahun_sekarang = date("Y");
                                                @endphp
                                                @for ($tahun = $tahun_mulai; $tahun<= $tahun_sekarang; $tahun++)
                                                    <option value="{{$tahun}}"{{$tahun_sekarang == $tahun ? "selected" : "" }}>{{$tahun}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                        <div class="card-body">
                                            <div class="text-center mt-3">
                                                @can('recap.print')
                                                    <button type="submit" name="cetak" class="btn btn-light px-5"><i class="bx bx-printer"></i>Cetak</button>
                                                @endcan
                                                @can('recap.export')
                                                    <button type="submit" name="export" class="btn btn-light px-5"><i class="bx bx-export"></i>Export to Excel</button>
                                                @endcan
                                            </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

    </script>
@endpush
