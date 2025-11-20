@extends('backend.layouts.app')
@section('title','Set Working Hour')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Employee Working Hour</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-responsive table-borderless">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <td>{{ $employee->nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $employee->first_name .' '. $employee->last_name }} </td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <form action="">
                        <table class="table table-responsive table-borderless">
                            <thead>
                                <tr>
                                    <th>HARI</th>
                                    <th class="text-center">JAM KERJA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="align-middle">Senin</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Senin</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Senin</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Selasa</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Rabu</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Kamis</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Jumat</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Sabtu</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="align-middle">Minggu</td>
                                    <td>
                                        <select name="idwk" id="idwk" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($workinghours as $d )
                                                <option value="{{$d->id}}">{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="col-6">
                    <table class="table table-responsive table-borderless">
                        <thead>
                            <tr>
                                <th colspan="6" class="text-center" style="text-align: center;">Master Jam Kerja</th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Awal Masuk</th>
                                <th>Jam Masuk</th>
                                <th>Akhir Masuk</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workinghours as $item)
                            <tr>
                                <th scope="row" class="align-middle text-center">{{ $loop->iteration }}</th>
                                    <td class="align-middle" data-name="name">{{ $item->name }}</td>
                                    <td class="align-middle">{{ date('H:i', strtotime($item->start_time)) }}</td>
                                    <td class="align-middle" data-name="entry_time">{{ date('H:i', strtotime($item->entry_time)) }}</td>
                                    <td class="align-middle" data-name="out_time">{{ date('H:i', strtotime($item->end_time)) }}</td>
                                    <td class="align-middle" data-name="out_time">{{ date('H:i', strtotime($item->out_time)) }}</td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

