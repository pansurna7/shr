@extends('backend.layouts.app')
@section('title','Working Hours')
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
        <div class="breadcrumb-title pe-3">Working Hours List</div>
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
                @can('workinghours.create')
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
                <table class="table mb-0" id="tblWorkingHour">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Nama Jam Kerja</th>
                            <th scope="col" class="text-center align-middle">Awal Jam Masuk</th>
                            <th scope="col" class="text-center align-middle">Jam Masuk</th>
                            <th scope="col" class="text-center align-middle">Akhir Jam Masuk</th>
                            <th scope="col" class="text-center align-middle">Jam Pulang</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($working_hours)>0)
                            @foreach ($working_hours as $item )
                                <tr>
                                    <th scope="row" class="align-middle text-center">{{ $loop->iteration }}</th>
                                    <td class="align-middle" data-name="name">{{ $item->name }}</td>
                                    <td class="align-middle">{{ date('H:i', strtotime($item->start_time)) }}</td>
                                    <td class="align-middle" data-name="entry_time">{{ date('H:i', strtotime($item->entry_time)) }}</td>
                                    <td class="align-middle" data-name="out_time">{{ date('H:i', strtotime($item->end_time)) }}</td>
                                    <td class="align-middle" data-name="out_time">{{ date('H:i', strtotime($item->out_time)) }}</td>

                                    <td class="align-middle">{{ $item->created_at?->format('Y-m-d') }}</td>
                                    <td class="align-middle">{{ $item->updated_at?->format('Y-m-d') }}</td>
                                    <td class="text-center align-middle">
                                        @can('workinghours.edit')
                                            <a href="{{ route('workinghour.edit', $item->id)}}" class="btn btn-sm btn-warning edit" id="{{$item->id}}">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $item->id }}" action="{{ route('workinghour.delete', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('workinghours.delete')
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
    <div class="modal fade" id="modal-add-workinghours" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Create New Working Hour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <form id="form-workinghour" method="POST"  action="{{route('workinghour.store')}}" class="needs-validation" autocomplete="off">
                        @csrf
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Nama JK</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Awal Jam Masuk</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="time" class="form-control time" id="awaljm" name="awaljm" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Jam Masuk</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="date" class="form-control time" id="entery_time" name="entry_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Akhir Jam Masuk</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control time" id="end_time" name="end_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="groupName" class="col-sm-3 col-form-label">Jam Pulang</label>
                            <div class="col-sm-9">
                                <div class="col-md-9">
                                    <input type="text" class="form-control time" id="out_time" name="out_time">
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
    <div class="modal fade" id="modal-edit-workinghour" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Update Working Hour</h5>
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
            new DataTable('#tblWorkingHour',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });

            flatpickr(".time", {
                // ✅ Opsi utama untuk mengaktifkan pemilih waktu
                enableTime: true,

                // ✅ Opsi utama untuk menyembunyikan kalender tanggal
                noCalendar: true,

                // Opsi Tambahan (Opsional)
                dateFormat: "H:i:ss", // Format waktu yang akan ditampilkan (misalnya 14:30)
                time_24hr: true,    // Menggunakan format 24 jam
                defaultDate: "07:00:00" // Waktu default saat picker dibuka

            });

            $("#btnCreate").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-add-workinghours")
                modal.modal('show')
                $("#name").val("");
            });


            $(".edit").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-edit-workinghour");
                let id = $(this).attr('id');
                // alert(id);

                $.ajax({
                    type: "POST",
                    url: "/workinghour/edit",
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

            $(document).on('submit', '#form-edit-workinghour', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');

                $.ajax({
                    type: "POST", // Metode harus POST karena kita menggunakan spoofing PUT
                    url: '/workinghour/update/' + id,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        // $('#tblDepartement').DataTable().ajax.reload(null,false);
                         //   window.location.reload();
                        window.location.href = "workinghours";
                    },

                });
                $('#modal-edit-workinghour').modal('hide');
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
