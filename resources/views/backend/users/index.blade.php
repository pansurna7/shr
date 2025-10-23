@extends('backend.layouts.app')
@section('title','Users')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Users List</div>
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

                <a type="button" class="btn btn-light" href="{{ route('users.create') }}">
                    <i class="lni lni-circle-plus"></i>Add New</a>

            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblUser">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">User Name</th>
                            <th scope="col" class="text-center align-middle">email</th>
                            <th scope="col" class="text-center align-middle">Role</th>
                            <th scope="col" class="text-center align-middle">Status</th>
                            <th scope="col" class="text-center align-middle">Join At</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($users)>0)
                            @foreach ($users as $user )
                                    <tr>
                                    <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                    <td class="align-middle">{{ $user->name }}</td>
                                    <td class="align-middle">{{$user->email}}</td>
                                    <td class="align-middle">
                                        @forelse ($user->roles as $role)
                                            <span class="badge {{$role->name == 'Super Admin' ? 'bg-danger' : 'bg-primary'}} ">{{ $role->name }}</span>
                                        @empty
                                            No roles assigned
                                        @endforelse
                                    </td>
                                     <td class="align-middle">
                                        <span class="badge {{$user->status ? 'bg-success' : 'bg-danger'}}">
                                            {{$user->status ? 'Active' :'Inctive' }}
                                        </span>
                                    </td>

                                     <td class="align-middle">{{$user->created_at?->format('Y-m-d')}}</td>
                                    <td class="text-center align-middle">
                                        @can('user.edit')
                                            <a href="{{ route('users.edit', $user->id)}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.delete', $user->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('user.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $user->id }}">
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
            new DataTable('#tblUser',{
                columnDefs:[
                                {targets:0, orderable:false},
                                {targets:4, orderable:false},
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
