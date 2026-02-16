@extends('backend.layouts.app')
@section('title','Leave/Cuti')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Leave List</div>
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
                @can('leave.create')
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
                <table class="table mb-0" id="tblLeave">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Name</th>
                            <th scope="col" class="text-center align-middle">quota</th>
                            <th scope="col" class="text-center align-middle">status</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaves as $d  )
                            <tr>
                                <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                <td class="align-middle">{{ $d->name }}</td>
                                <td class="align-middle text-end">{{ $d->quota }}</td>
                                <td class="align-middle text-center">
                                    <span class="badge {{ $d->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $d->is_active ? 'Active' : 'Non-active' }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">{{ $d->created_at }}</td>
                                <td class="align-middle text-center">{{ $d->updated_at }}</td>
                                <td class="text-center align-middle">
                                        @can('leave.edit')
                                            <a href="{{ route('leave.edit', $d->id)}}" class="btn btn-sm btn-warning edit" id="{{$d->id}}">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $d->id }}" action="{{ route('leave.delete', $d->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('leave.delete')
                                            <a  class="btn delete-button btn-danger btn-sm" data-id="{{ $d->id }}">
                                                <i class="bx bx-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i> Delete
                                            </a>
                                        @endcan
                                    </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- {{ $roles->links() }} --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-add-leave" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Create New Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <form id="form-leave" method="POST"  action="{{route('leave.store')}}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Name Leave</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="quota" class="col-sm-3 col-form-label">Quota</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="number" class="form-control" id="quota" name="quota" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="is_active" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <div class="form-check form-switch mt-2">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                    <label class="form-check-label text-white" for="is_active" id="status-label">Active</label>
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
    <div class="modal fade" id="modal-edit-leave" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Update Leave</h5>
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
            new DataTable('#tblLeave',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });



            $("#btnCreate").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-add-leave")
                modal.modal('show')
                $("#name").val("");
                $("#quota").val("");
                $("#quota").on("input", function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            });


            $(".edit").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-edit-leave");
                let id = $(this).attr('id');
                // alert(id);

                $.ajax({
                    type: "POST",
                    url: "/leave/edit",
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
                        $("#quota").on("input", function () {
                            this.value = this.value.replace(/[^0-9]/g, '');
                        });
                    }
                });

            });

            $(document).on('submit', '#form-edit-leave', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');

                $.ajax({
                    type: "POST", // Metode harus POST karena kita menggunakan spoofing PUT
                    url: '/leave/update/' + id,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        // $('#tblDepartement').DataTable().ajax.reload(null,false);
                         //   window.location.reload();
                        window.location.href = "leaves";
                    },

                });
                $('#modal-edit-leave').modal('hide');
            });



        });

        // popup delete
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
