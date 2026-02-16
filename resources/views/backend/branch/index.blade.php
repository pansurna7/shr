@extends('backend.layouts.app')
@section('title','Branch')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Branch List</div>
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
                @can('branch.create')
                    <a type="button" class="btn btn-light" id=btnCreate>
                        <i class="lni lni-circle-plus"></i>Add New</a>
                @endcan
            </div>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblBranch">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Code</th>
                            <th scope="col" class="text-center align-middle">Name</th>
                            <th scope="col" class="text-center align-middle">Address</th>
                            {{-- <th scope="col" class="text-center align-middle">Map</th>
                            <th scope="col" class="text-center align-middle">Radius</th> --}}
                            <th scope="col" class="text-center align-middle">Meal Allowance</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($branches)>0)
                            @foreach ($branches as $item )
                                <tr>
                                    <th scope="row" class="align-middle text-center">{{ $loop->iteration }}</th>
                                    <td class="align-middle" data-name="code">{{ $item->code }}</td>
                                    <td class="align-middle" data-name="name">{{ $item->name }}</td>
                                    <td class="align-middle" data-name="address">{{ $item->address }}</td>
                                    {{-- <td class="align-middle" data-name="location">{{ $item->location }}</td>
                                    <td class="align-middle" data-name="radius">{{ $item->radius }}</td> --}}
                                    <td class="align-middle" data-name="meal_allowance">{{ $item->meal_allowance }}</td>
                                    <td class="align-middle">{{ $item->created_at?->format('Y-m-d') }}</td>
                                    <td class="align-middle">{{ $item->updated_at?->format('Y-m-d') }}</td>
                                    <td class="text-center align-middle">
                                        @can('branch.edit')
                                            <a href="{{ route('branch.edit', $item->id)}}" class="btn btn-sm btn-warning edit" id="{{$item->id}}">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $item->id }}" action="{{ route('branch.delete', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('branch.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $item->id }}">
                                                <i class="bx bx-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i> Delete
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                {{-- <td colspan="6" class="text-center">No Data Found!</td> --}}
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- {{ $roles->links() }} --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-add-branch" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Create New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <form id="form-branch" method="POST"  action="{{route('branch.store')}}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Kode Branch</label>
                            <div class="col-sm-9">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="kode" name="kode" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Nama Branch</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Koordinat</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Radius</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="radius" name="radius" required>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Meal Allowance</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="mela_allowance" name="meal_allowance">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="text-center mt-5">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="lni lni-arrow-left-circle"></i>Close</button>
                                <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Save</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-edit-branch" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Update Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white" id="loadEditForm">

                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function () {
			'use strict'

			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.querySelectorAll('.needs-validation')

			// Loop over them and prevent submission
			Array.prototype.slice.call(forms)
				.forEach(function (form) {
				    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
					    form.classList.add('was-validated')
				    }, false)
				})
			})()

        $(document).ready(function () {
            new DataTable('#tblBranch',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });

            $("#btnCreate").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-add-branch")
                modal.modal('show')
                $("#kode").val("");
                $("#name").val("");
                $("#address").val("");
                $("#location").val("");
                $("#radius").val("");
                $("#meal_allowance").val("");
            });


            $(".edit").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-edit-branch");
                let id = $(this).attr('id');
                // alert(id);

                $.ajax({
                    type: "POST",
                    url: "/branch/edit",
                    cache: false,
                    data: {
                        // FIX 1: PHP variable must be enclosed in quotes to be a string in JS
                        // FIX 2: Use a colon (:) for key-value pairs in JS objects, not an equals sign (=)
                        _token: "{{ csrf_token() }}",
                        id: id // Assuming 'id' is a JavaScript variable defined earlier
                    },
                    // FIX 3: Remove this line or set it to 'json'/'html'
                    // dataType: "dataType",
                    success: function (response) {
                        $("#loadEditForm").html(response);
                        modal.modal('show');
                    }
                });

            });

            $(document).on('submit', '#form-edit-branch', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');

                $.ajax({
                    type: "POST", // Metode harus POST karena kita menggunakan spoofing PUT
                    url: '/branch/update/' + id,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        // $('#tblDepartement').DataTable().ajax.reload(null,false);
                         //   window.location.reload();
                        window.location.href = "branches";
                    },

                });
                $('#modal-edit-branch').modal('hide');
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
