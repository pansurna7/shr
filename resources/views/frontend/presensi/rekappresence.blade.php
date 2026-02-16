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
                        {{-- <form method="POST" action="{{route('rekap.cetak')}}" target="_blank">
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
                                                    <button type="submit" name="export-excel" class="btn btn-light px-5"><i class="bx bx-export"></i>Export to Excel</button>
                                                @endcan
                                            </div>
                                </div>
                            </div>
                        </form> --}}
                        <form action="{{ route('rekapPresence.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Dari Tanggal</label>
                                    <input type="date" name="dari" class="form-control" value="{{ $tgl_mulai }}">
                                </div>
                                <div class="col-md-3">
                                    <label>Sampai Tanggal</label>
                                    <input type="date" name="sampai" class="form-control" value="{{ $tgl_selesai }}">
                                </div>
                                {{-- <div class="col-md-4">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'pdf']) }}" target="_blank" class="btn btn-danger">Cetak PDF</a>
                                </div> --}}
                                <div class="col-md-4">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">Tampilkan</button>

                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'pdf']) }}" target="_blank" class="btn btn-danger">Cetak PDF</a>

                                    <a href="{{ request()->fullUrlWithQuery(['type' => 'excel']) }}" class="btn btn-success">Export Excel</a>
                                </div>
                            </div>
                        </form>
                        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Attendance Summary</h4>
                <p class="text-muted small">Periode: <span class="badge bg-info text-dark">{{ $periode_teks }}</span></p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover border-light align-middle" id="tblRekap">
                <thead class="bg-light text-muted">
                    <tr style="font-size: 0.8rem;">
                        <th class="border-0">EMPLOYEE</th>
                        <th class="border-0 text-center">SCHEDULED</th>
                        <th class="border-0 text-center text-success">PRESENT</th>
                        <th class="border-0 text-center text-primary">IZIN</th>
                        <th class="border-0 text-center text-danger">SAKIT</th>
                        <th class="border-0 text-center text-warning">CUTI</th>
                        <th class="border-0 text-center text-dark">ALPA</th>
                        <th class="border-0 text-center">PERC (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap as $r)
                        @php
                            $percentage = $r->hari_kerja_efektif > 0 ? ($r->hadir / $r->hari_kerja_efektif) * 100 : 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-bold text-uppercase">{{ $r->first_name }} {{ $r->last_name }}</div>
                                <small class="text-muted">{{ $r->position_name }}</small>
                            </td>
                            <td class="text-center">{{ $r->hari_kerja_efektif }}</td>
                            <td class="text-center fw-bold text-success">{{ $r->hadir }}</td>
                            <td class="text-center">{{ $r->izin }}</td>
                            <td class="text-center">{{ $r->sakit }}</td>
                            <td class="text-center">{{ $r->cuti }}</td>
                            <td class="text-center">
                                <span class="badge {{ $r->alpa > 0 ? 'bg-danger' : 'bg-light text-muted' }}">
                                    {{ $r->alpa }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="progress mb-1" style="height: 6px; width: 60px; margin: 0 auto;">
                                    <div class="progress-bar {{ $percentage < 90 ? 'bg-danger' : ($percentage < 100 ? 'bg-warning' : 'bg-success') }}"
                                        style="width: {{ $percentage }}%"></div>
                                </div>
                                <small class="fw-bold" style="font-size: 0.7rem;">{{ round($percentage) }}%</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-5 text-muted">
                                <i class="bx bx-info-circle font-30"></i><br>
                                Tidak ada data untuk periode ini. Silakan pilih tanggal dan klik Tampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
         new DataTable('#tblRekap',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });
    </script>
@endpush
