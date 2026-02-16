@extends('backend.layouts.app')
@section('title','Holiday/Libur Nasional')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Holiday List</div>
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
                {{-- Tombol Download Template --}}
                <a href="{{ route('holiday.download-template') }}" class="btn btn-outline-info">
                    <i class="bx bx-download"></i> Template
                </a>

                {{-- Tombol Import --}}
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-import-holiday">
                    <i class="bx bx-upload"></i> Import
                </button>

                @can('holiday.create')
                    <a type="button" class="btn btn-primary" id="btnCreate">
                        <i class="bx bx-plus-circle"></i> Add New
                    </a>
                @endcan
            </div>
        </div>
    </div>
	<hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblholiday">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Date</th>
                            <th scope="col" class="text-center align-middle">Description</th>
                            <th scope="col" class="text-center align-middle">Created</th>
                            <th scope="col" class="text-center align-middle">Updated</th>
                            <th scope="col" class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($holidays as $d  )
                            <tr>
                                <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                <td class="align-middle fw-bold text-primary">
                                    {{ \Carbon\Carbon::parse($d->holiday_date)->format('d M Y') }}
                                </td>
                                <td class="align-middle">{{ $d->description ?? $d->name }}</td>
                                <td class="align-middle text-center small">
                                    {{ $d->created_at ? $d->created_at->diffForHumans() : '-' }}
                                </td>
                                <td class="align-middle text-center small">
                                    {{ $d->updated_at ? $d->updated_at->format('d/m/H:i') : '-' }}
                                </td>
                                <td class="text-center align-middle">
                                        @can('holiday.edit')
                                            <a href="{{ route('holiday.edit', $d->id)}}" class="btn btn-sm btn-warning edit" id="{{$d->id}}">
                                                <i class="bx bx-pencil" data-toggle="tooltip" data-placement="top" title="Edit"></i>
                                                Edit
                                            </a>
                                        @endcan

                                        <form id="delete-form-{{ $d->id }}" action="{{ route('holiday.delete', $d->id) }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                        @can('holiday.delete')
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
    {{-- modal add --}}
    <div class="modal fade" id="modal-add-holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Create New Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white">
                    <form action="{{ route('holiday.store') }}" method="POST" class="needs-validation">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tanggal Libur</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="date" name="holiday_date" id="holiday_date" class="form-control @error('holiday_date') is-invalid @enderror" required>
                                </div>
                                @error('holiday_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold">Keterangan / Nama Libur</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Tahun Baru Imlek" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="col-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-switch" type="checkbox" name="is_national" id="isNational" checked>
                                    <label class="form-check-label text-muted" for="isNational">Tetapkan sebagai Libur Nasional</label>
                                </div>
                            </div> --}}

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bx bx-save me-1"></i> Simpan Data
                                </button>
                                <button type="reset" class="btn btn-light px-4 ms-2">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal edit --}}
    <div class="modal fade" id="modal-edit-holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Update holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white" id="loadEditForm">

                </div>

            </div>
        </div>
    </div>

    {{-- modal import --}}
    <div class="modal fade" id="modal-import-holiday" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light">
                <form id="form-import-holiday" action="{{ route('holiday.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-white">Import Holiday Excel</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-white">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih File Excel</label>
                            <input type="file" name="file_excel" id="file_excel" class="form-control" accept=".xlsx, .xls" required>
                        </div>

                        <div id="progress-container" class="d-none">
                            <label class="mb-2 small">Memproses Data...</label>
                            <div class="progress" style="height: 20px;">
                                <div id="import-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                    role="progressbar" style="width: 0%;">0%</div>
                            </div>
                            <p class="text-warning small mt-2"><i class="bx bx-error-circle"></i> Mohon jangan tutup jendela ini atau merefresh halaman.</p>
                        </div>
                    </div>
                    <div class="modal-footer" id="modal-footer-import">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-import">
                            <i class="bx bx-upload"></i> Mulai Import
                        </button>
                    </div>
                </form>
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
            new DataTable('#tblholiday',{
            columnDefs:[
                            {targets:0, orderable:false},
                            {targets:5, orderable:false},
                        ]
            });

            $("#btnCreate").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-add-holiday")
                modal.modal('show')
                $("#holiday_date").val("");
                $("#name").val("");
            });


           // Bagian Edit Button Click
            $(".edit").click(function (e) {
                e.preventDefault();
                let modal = $("#modal-edit-holiday");
                let id = $(this).attr('id');

                $.ajax({
                    type: "POST",
                    url: "{{ route('holiday.edit') }}", // Menggunakan named route lebih aman
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function (response) {
                        $("#loadEditForm").html(response);
                        modal.modal('show');
                    },
                    error: function() {
                        alert("Gagal mengambil data.");
                    }
                });
            });

            $(document).on('submit', '#form-edit-holiday', function(e) {
                e.preventDefault();
                var form = $(this);
                var id = form.data('id');

                $.ajax({
                    type: "POST", // Metode harus POST karena kita menggunakan spoofing PUT
                    url: '/holiday/update/' + id,
                    data: form.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = "holidays";
                    },

                });
                $('#modal-edit-holiday').modal('hide');
            });

            // progress bar
            $('#form-import-holiday').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let progressBar = $('#import-progress-bar');
                let progressContainer = $('#progress-container');
                let btnSubmit = $('#btn-submit-import');
                let btnClose = $('.btn-close, .btn-secondary');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        // Menghitung persentase upload
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                progressBar.css('width', percentComplete + '%');
                                progressBar.html(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    beforeSend: function() {
                        // Persiapan tampilan sebelum kirim
                        progressContainer.removeClass('d-none');
                        btnSubmit.prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin"></i> Uploading...');
                        btnClose.addClass('d-none'); // Sembunyikan tombol batal agar tidak di-cancel
                        progressBar.css('width', '0%').html('0%');
                    },
                    success: function(response) {
                        // Jika sukses
                        progressBar.removeClass('bg-success').addClass('bg-primary');
                        progressBar.html('Finishing...');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data hari libur telah berhasil diimport.',
                            showConfirmButton: true
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        // Jika gagal
                        progressContainer.addClass('d-none');
                        btnSubmit.prop('disabled', false).html('<i class="bx bx-upload"></i> Mulai Import');
                        btnClose.removeClass('d-none');

                        let errorMsg = 'Terjadi kesalahan saat memproses file.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        Swal.fire('Gagal!', errorMsg, 'error');
                    }
                });
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
