@extends('backend.layouts.app')
@section('title','Employees')
@push('css')
<style>
.img-circle {
    /* Atur ukuran gambar (kunci agar lingkaran terlihat sempurna) */
    width: 60px;
    height: 60px;
    /* Penting: Pastikan lebar dan tinggi sama (square) */

    /* Properti utama untuk membuat lingkaran */
    border-radius: 50%;

    /* Opsi tambahan: Agar gambar tidak terdistorsi saat diubah ukurannya */
    object-fit: cover;

    /* Opsi tambahan: Border melingkar */
    border: 2px solid #ccc;
}
</style>
@endpush



@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Employee List</div>
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
                @can('employee.create')
                    <a type="button" class="btn btn-light" href="{{ route('employee.create') }}" id="btnAdd">
                        <i class="lni lni-circle-plus"></i>Add New</a>
                @endcan
            </div>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblEmployee">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Nik</th>
                            <th scope="col" class="text-center align-middle">Nama</th>
                            <th scope="col" class="text-center align-middle">Jabatan</th>
                            <th scope="col" class="text-center align-middle">No.Telp</th>
                            <th scope="col" class="text-center align-middle">Foto</th>
                            <th scope="col" class="text-center align-middle">Departement</th>
                            <th scope="col" class="text-center align-middle">Branch</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            {{-- <th scope="col" class="text-center align-middle">Updated</th> --}}
                            <th scope="col" class="text-center align-middle" style="width: 20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($employees)>0)
                            @foreach ($employees as $d )
                                    <tr>
                                    <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                    <td class="align-middle">{{ $d->nik }}</td>
                                    <td class="align-middle">{{ $d->first_name ." " . $d->last_name }}</td>
                                    <td class="align-middle">{{ $d->position->name }}</td>
                                    <td class="align-middle">{{ $d->mobile }}</td>
                                    <td class="align-middle"><img class="img-circle" src="{{ asset('storage/'. $d->avatar) }}"
                                        alt="Foto Profile">
                                    </td>
                                    <td class="align-middle">{{ $d->position->departement->name }}</td>
                                    <td class="align-middle">{{ $d->branch->name }}</td>
                                    <td class="align-middle">{{ $d->created_at?->format('Y-m-d') }}</td>
                                    {{-- <td class="align-middle">{{ $d->updated_at?->format('Y-m-d') }}</td> --}}
                                    <td class="text-center align-middle">
                                        @can('employee.edit')
                                            <a href="{{ route('employee.edit', $d->id)}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $d->id }}" action="{{ route('employee.delete', $d->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('employee.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $d->id }}">
                                                <i class="bx bx-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i> Delete
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- {{ $roles->links() }} --}}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            new DataTable('#tblEmployee',{
                columnDefs:[
                                {targets:0, orderable:false},
                                {targets:6, orderable:false},
                            ]
            });

            $("#btnAdd").click(function (e) {

            // alert('ok');
                $('#employeeForm').trigger('reset');
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
