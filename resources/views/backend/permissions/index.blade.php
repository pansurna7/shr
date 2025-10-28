@extends('backend.layouts.app')
@section('title','Permissions')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Permission List</div>
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
                @can('permission.create')
                    <a type="button" class="btn btn-light" href="{{ route('permissions.create') }}">
                        <i class="lni lni-circle-plus"></i>Add New</a>
                @endcan

            </div>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblPermission">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Group Name</th>
                            <th scope="col" class="text-center align-middle">Guard Name</th>
                            <th scope="col" class="text-center align-middle">Permission</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($permissions)>0)
                            @foreach ($permissions->sortBy('group_name') as $permission )
                                    <tr>
                                    <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                    <td class="align-middle">{{ $permission->group_name }}</td>
                                    <td class="align-middle">{{ $permission->guard_name }}</td>
                                    <td class="align-middle">{{ $permission->name }}</td>
                                    <td class="align-middle">{{ $permission->created_at?->format('Y-m-d') }}</td>
                                    <td class="align-middle">{{ $permission->updated_at?->format('Y-m-d') }}</td>
                                   <td class="text-center align-middle">
                                        @can('permission.edit')
                                            <a href="{{ route('permissions.edit', $permission->id)}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $permission->id }}" action="{{ route('permissions.delete', $permission->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('permission.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $permission->id }}">
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
          new DataTable('#tblPermission',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:6, orderable:false},
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
