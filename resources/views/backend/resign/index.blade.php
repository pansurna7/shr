@extends('backend.layouts.app')
@section('title', 'Resign')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Resign History</div>
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
                @can('resignation.create')
                    <button type="button" class="btn btn-primary" id="btnCreate">
                        <i class="bx bx-plus-circle"></i> Add New
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <hr />

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tblResign">
                    <thead>
                        <tr>
                            <th class="text-center">Karyawan</th>
                            <th class="text-center">Tgl Keluar</th>
                            <th class="text-center">Alasan</th>
                            <th class="text-center">Dokumen</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resignations as $r)
                            <tr>
                                <td>{{ $r->employee->first_name }} <br><small>{{ $r->employee->nik }}</small></td>
                                <td>{{ date('d/m/Y', strtotime($r->resign_date)) }}</td>
                                <td><span class="badge bg-danger">{{ $r->reason }}</span></td>
                                <td>
                                    @if ($r->document)
                                        <a href="{{ asset('storage/' . $r->document) }}" target="_blank"
                                            class="btn btn-sm btn-light">Lihat File</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('resignation.delete')
                                        <form action="{{ route('resign.delete', $r->id) }}" method="POST"
                                            id="form-delete-{{ $r->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-secondary btnDelete"
                                                data-id="{{ $r->id }}">
                                                <i class="bx bx-trash"></i> Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalResign" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Form Pengunduran Diri / Resign</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formResign" action="{{ route('resign.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Karyawan (Hanya yang Aktif)</label>
                                <select name="employee_id" class="form-select select2-modal" required>
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach ($activeEmployees as $emp)
                                        <option value="{{ $emp->id }}">
                                            {{ $emp->nik }} - {{ $emp->first_name }} {{ $emp->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-danger">*Karyawan yang sudah keluar tidak akan muncul di sini.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Efektif Berhenti</label>
                                <input type="date" name="resign_date" class="form-control" required
                                    value="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Alasan Berhenti</label>
                                <select name="reason" class="form-select" required>
                                    <option value="">-- Pilih Alasan --</option>
                                    <option value="Resign (Kemauan Sendiri)">Resign (Kemauan Sendiri)</option>
                                    <option value="Habis Kontrak">Habis Kontrak</option>
                                    <option value="Pemutusan Hubungan Kerja (PHK)">PHK</option>
                                    <option value="Pensiun">Pensiun</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Dokumen Pendukung (PDF/JPG)</label>
                                <input type="file" name="document" class="form-control">
                                <div class="form-text">Contoh: Surat Resign bertanda tangan atau Paklaring.</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Keterangan Tambahan</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Simpan & Nonaktifkan Karyawan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#tblResign').DataTable();
            // Inisialisasi Select2 dalam Modal
            $('.select2-modal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalResign'),
                width: '100%'
            });

            // Tombol buka modal
            $('#btnCreate').on('click', function() {
                $('#formResign')[0].reset();
                $('.select2-modal').val(null).trigger('change');
                $('#modalResign').modal('show');
            });
        });
        $(document).on('click', '.btnDelete', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let form = $('#form-delete-' + id);

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data resign akan dihapus dan status karyawan serta akun login akan diaktifkan kembali!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan Resign!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading sebentar agar lebih profesional
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    // Submit form
                    form.submit();
                }
            });
        });
    </script>
@endpush
