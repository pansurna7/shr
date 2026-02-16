@extends('backend.layouts.app')
@section('title','Departements')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Departemnt List</div>
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
                @can('departement.create')
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
                <table class="table mb-0" id="tblDepartement">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Code</th>
                            <th scope="col" class="text-center align-middle">Name</th>

                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($deps)>0)
                            @foreach ($deps->sortBy('name') as $item )
                                <tr>
                                    <th scope="row" class="align-middle">{{ $loop->iteration }}</th>
                                    <td class="align-middle" data-name="code">{{ $item->code }}</td>
                                    <td class="align-middle" data-name="name">{{ $item->name }}</td>
                                    <td class="align-middle">{{ $item->created_at?->format('Y-m-d') }}</td>
                                    <td class="align-middle">{{ $item->updated_at?->format('Y-m-d') }}</td>
                                    <td class="text-center align-middle">
                                        @can('departement.edit')
                                            <a href="{{ route('departement.edit', $item->id)}}" class="btn btn-sm btn-warning edit" id="{{$item->id}}">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $item->id }}" action="{{ route('departement.delete', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('departement.delete')
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
    <div class="modal fade" id="modal-add-departement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Create New Departement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <form id="form-departement" method="POST"  action="{{route('departement.store')}}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Kode</label>
                            <div class="col-sm-9">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="kode" name="kode" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" name="name" required>
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
    <div class="modal fade" id="modal-edit-departement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Update Departement</h5>
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
            new DataTable('#tblDepartement',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });

            $("#btnCreate").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-add-departement")
                modal.modal('show')
                $("#kode").val("");
                $("#name").val("");
            });


            $(".edit").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-edit-departement");
                let id = $(this).attr('id');
                // alert(id);

                $.ajax({
                    type: "POST",
                    url: "/departement/edit",
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

            $(document).on('submit', '#form-edit-departement', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');

                $.ajax({
                    type: "POST", // Metode harus POST karena kita menggunakan spoofing PUT
                    url: '/departement/update/' + id,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        // $('#tblDepartement').DataTable().ajax.reload(null,false);
                         //   window.location.reload();
                        window.location.href = "departements";
                    },

                });
                $('#modal-edit-departement').modal('hide');
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
