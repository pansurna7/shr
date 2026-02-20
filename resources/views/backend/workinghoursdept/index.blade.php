@extends('backend.layouts.app')
@section('title','List Working Hours Departement')
@push('css')
    <style>
                /* Menargetkan input angka di Timepicker */
        .flatpickr-time input.flatpickr-hour,
        .flatpickr-time input.flatpickr-minute {
            height: 20px;
            font-size: 15px; /* Membuat angka jam/menit lebih besar */
            width: 20px;
        }

        /* Menyesuaikan tombol panah atas/bawah */
        .flatpickr-time .flatpickr-am-pm {
            height: 20px;
            font-size: 10px;
        }
    </style>
@endpush
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Working Hours Departement</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                @can('workinghoursdepts.create')
                    <a type="button" class="btn btn-light" href="{{route('whd.create')}}">
                        <i class="lni lni-circle-plus"></i>Add New</a>
                @endcan
            </div>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblWorkingHourDept">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Branch</th>
                            <th scope="col" class="text-center align-middle">Departement</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($whd as $d)
                            <tr>
                                <td class="text-center">{{ $loop->iteration}}</td>
                                <td>{{ strtoupper( $d->branch_name)}}</td>
                                <td>{{ strtoupper($d->dept_name)}}</td>
                                <td  class="text-center">{{ \Carbon\carbon::parse($d->created_at)->format('H:i')}}</td>
                                <td class="text-center">{{ \Carbon\carbon::parse($d->updated_at)->format('H:i')}}</td>
                                <td class="text-center align-middle">
                                    @can('workinghoursdepts.edit')
                                        <a href="{{ route('whd.edit', $d->id)}}" class="btn btn-sm btn-warning edit" id="{{$d->id}}">
                                            <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                            Edit
                                        </a>
                                    @endcan
                                    @can('workinghoursdepts.show')
                                        <a href="{{ route('whd.show', $d->id)}}" class="btn btn-sm btn-success edit" id="{{$d->id}}">
                                            <i class="bx bx-show-alt" data-toggle="tooltip" data-placement="top" title="Detail"></i>
                                            Detail
                                        </a>
                                    @endcan

                                    <form id="delete-form-{{ $d->id }}" action="{{ route('whd.delete', $d->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                    @can('workinghoursdepts.delete')
                                        <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $d->id }}">
                                            <i class="bx bx-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i> Delete
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            new DataTable('#tblWorkingHourDept',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function () {
                    const itemId = this.dataset.id;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't to deleted this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${itemId}`).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
