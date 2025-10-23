@extends('backend.layouts.app')
@section('title','Roles')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role List</div>
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
                @can('role.create')
                    <a type="button" class="btn btn-light" href="{{ route('roles.create') }}">
                        <i class="lni lni-circle-plus"></i>Add New</a>
                @endcan

            </div>
        </div>
    </div>
    <!--end breadcrumb-->

    <hr/>
    <div class="card">
        <div class="card-body">
            <div class=" table-responsive table-bordered">
                <table class="table mb-0" id="tblRole">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center w-5">#</th>
                            <th scope="col" class="text-center w-10">Role Name</th>
                            <th scope="col" class="text-center w-50">Permission</th>
                            <th scope="col" class="text-center w-15">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($roles)>0)
                            @foreach ($roles as $role )
                                <tr>
                                    <td class="align-middle fs-6">{{ $loop->iteration }}</td>
                                    <td class="align-middle fs-6">{{ $role->name }}</td>

                                        <td>
                                            @foreach ($role->permissions->sortBy('name') as $permission )
                                                <span class=" top-0 start-100  badge rounded-pill bg-success font-14 mt-3 mr-3">{{$permission->name}}</span>
                                            @endforeach
                                        </td>
                                    <td class="text-center align-middle">
                                        {{-- @can('role.show')
                                            <a href="{{ route('roles.show')}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-spreadsheet" data-toggle="tooltip" data-placement="top" title="Show"></i>
                                            </a>
                                        @endcan --}}
                                        @can('role.edit')
                                            <a href="{{ route('roles.edit', $role->id)}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $role->id }}" action="{{ route('roles.delete', $role->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('role.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $role->id }}">
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

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        new DataTable('#tblRole',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:3, orderable:false},
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
