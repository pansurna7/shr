@extends('backend.layouts.app')
@section('title','Menus')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Menu List</div>
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

                <a type="button" class="btn btn-light" href="{{ route('menus.create') }}">
                    <i class="lni lni-circle-plus"></i>Add New</a>

            </div>
        </div>
    </div>
    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblMenu">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Name</th>
                            <th scope="col" class="text-center align-middle">Description</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($menus)>0)
                            @foreach ($menus as $menu )
                                <tr>
                                    <th scope="row" class="align-middle text-center">{{ $loop->iteration }}</th>
                                    <td class="align-middle"><code>{{ $menu->name }}</code></td>
                                    <td class="align-middle">{{$menu->description}}</td>
                                    <td class="align-middle">{{$menu->created_at?->format('Y-m-d')}}</td>
                                    <td class="align-middle">{{$menu->updated_at?->format('Y-m-d')}}</td>
                                    <td class="text-center align-middle">
                                        @can('menu.builder')
                                            <a href="{{ route('menus.builder.index', $menu->id)}}" class="btn btn-sm btn-success">
                                                <i class="bx bx-building" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Builder
                                            </a>
                                        @endcan
                                        @can('menu.edit')
                                            <a href="{{ route('menus.edit', $menu->id)}}" class="btn btn-sm btn-warning">
                                                <i class="bx bx-edit-alt" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $menu->id }}" action="{{ route('menus.delete', $menu->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('menu.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $menu->id }}">
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
            new DataTable('#tblMenu',{
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
