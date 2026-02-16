@extends('backend.layouts.app')
@section('title', 'Announcements')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Announcements List</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                @can('announcement.create')
                    <button type="button" class="btn btn-light" id="btnCreate">
                        <i class="lni lni-circle-plus"></i> Add New
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <hr />

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Konten</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($announcements as $a)
                            <tr>
                                <td>{{ $a->title }}</td>
                                <td>{{ Str::limit($a->content, 40) }}</td>
                                <td>
                                    <small>{{ date('d/m/y', strtotime($a->start_date)) }} -
                                        {{ date('d/m/y', strtotime($a->end_date)) }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $a->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $a->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="javascript:;" class="btn btn-sm btn-warning me-2 btnEdit"
                                            data-id="{{ $a->id }}" data-title="{{ $a->title }}"
                                            data-content="{{ $a->content }}" data-start="{{ $a->start_date }}"
                                            data-end="{{ $a->end_date }}" data-active="{{ $a->is_active }}">
                                            <i class="bx bxs-edit text-white"></i>
                                        </a>
                                        <form action="{{ route('announcement.delete', $a->id) }}" method="POST"
                                            class="form-delete" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                <i class="bx bxs-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formAnnouncement" method="POST" class="modal-content" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Judul Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Isi Pengumuman</label>
                        <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Tgl Mulai</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tgl Berakhir</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div id="statusField" class="mb-2 d-none"> <label class="form-label">Status</label>
                        <select name="is_active" id="is_active" class="form-select text-dark">
                            <option value="1">Aktif</option>
                            <option value="0">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" id="fileLabel">Lampiran (File)</label>
                        <input type="file" name="file_name" id="file_name" class="form-control">
                        <small class="text-muted d-none" id="fileNote">Kosongkan jika tidak ingin mengubah file.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // --- NOTIFIKASI SWEETALERT ---
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ session('error') }}"
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Periksa Input',
                    html: "{!! implode('<br>', $errors->all()) !!}"
                });
            @endif

            // --- LOGIC MODAL ---

            // Tombol Add New
            $('#btnCreate').on('click', function() {
                $('#formAnnouncement')[0].reset();
                $('#methodField').html('');
                $('#modalTitle').text('Buat Pengumuman Baru');
                $('#btnSubmit').text('Simpan & Terbitkan');
                $('#statusField').addClass('d-none');
                $('#fileNote').addClass('d-none');
                $('#formAnnouncement').attr('action', "{{ route('announcement.store') }}");
                $('#modalAnnouncement').modal('show');
            });

            // Tombol Edit
            $(document).on('click', '.btnEdit', function() {
                let id = $(this).data('id');
                $('#title').val($(this).data('title'));
                $('#content').val($(this).data('content'));
                $('#start_date').val($(this).data('start'));
                $('#end_date').val($(this).data('end'));
                $('#is_active').val($(this).data('active'));

                $('#modalTitle').text('Edit Pengumuman');
                $('#btnSubmit').text('Update Data');
                // Manual inject hidden input agar tidak tertimpa reset
                $('#methodField').html('<input type="hidden" name="_method" value="PUT">');
                $('#statusField').removeClass('d-none');
                $('#fileNote').removeClass('d-none');

                let updateUrl = "{{ url('announcement/update') }}/" + id;
                $('#formAnnouncement').attr('action', updateUrl);
                $('#modalAnnouncement').modal('show');
            });

            // Konfirmasi Hapus dengan SWAL
            $(document).on('click', '.btn-delete', function(e) {
                let form = $(this).closest('form'); // Ambil form terdekat

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pengumuman dan lampiran akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Kirim form jika user klik Ya
                    }
                });
            });
        });
    </script>
@endpush
