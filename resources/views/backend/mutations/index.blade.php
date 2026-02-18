@extends('backend.layouts.app')
@section('title', 'Mutation')
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Mutations History</div>
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
                @can('mutation.create')
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
                <table class="table table-bordered align-middle" id="tblMutation">
                    <thead class="table-light">
                        <tr>
                            <th>Karyawan</th>
                            <th>Tgl Mutasi</th>
                            <th>Dari (Cabang/Jabatan)</th>
                            <th>Ke (Cabang/Jabatan)</th>
                            <th>Akses Lokasi Baru</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mutations as $m)
                            <tr>
                                <td>{{ $m->employee->first_name }} <br> <small>{{ $m->employee->nik }}</small></td>
                                <td>{{ date('d-m-Y', strtotime($m->mutation_date)) }}</td>
                                <td><small class="text-muted">{{ $m->oldBranch->name ?? '-' }} / {{ $m->oldPosition->name ?? '-' }}</small></td>
                                <td><b class="text-primary">{{ $m->newBranch->name ?? '-' }} / {{ $m->newPosition->name ?? '-' }}</b></td>
                                <td>
                                    @foreach ($m->employee->assigned_locations as $loc)
                                        <span class="badge bg-info text-dark mb-1">
                                            <i class="bx bx-map-pin"></i> {{ $loc->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    @can('mutation.edit')
                                        <button type="button" class="btn btn-sm btn-warning btnEdit"
                                            data-id="{{ $m->id }}"
                                            data-employee-id="{{ $m->employee_id }}"
                                            data-date="{{ $m->mutation_date }}"
                                            data-new-branch="{{ $m->new_branch_id }}"
                                            data-new-position="{{ $m->new_position_id }}"
                                            data-desc="{{ $m->description }}"
                                            data-locations="{{ json_encode($m->employee->assigned_locations->pluck('id')) }}">
                                            <i class="bx bx-pencil"></i> Edit
                                        </button>
                                    @endcan

                                    @can('mutation.delete')
                                        <form action="{{ route('mutation.delete', $m->id) }}" method="POST" class="d-inline form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                <i class="bx bx-trash"></i> Delete
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

    <div class="modal fade" id="modalMutation" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">Form Mutasi Karyawan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formMutation" method="POST">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Karyawan</label>
                                <select name="employee_id" id="employee_id" class="form-select select2-modal" required>
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->nik }} - {{ $emp->first_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Efektif</label>
                                <input type="date" name="mutation_date" id="mutation_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cabang Baru</label>
                                <select name="new_branch_id" id="new_branch_id" class="form-select" required>
                                    <option value="">Pilih Cabang</option>
                                    @foreach ($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Jabatan Baru</label>
                                <select name="new_position_id" id="new_position_id" class="form-select" required>
                                    <option value="">Pilih Jabatan</option>
                                    @foreach ($positions as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-primary">Titik Lokasi Absensi Baru (Multiple)</label>
                                <select name="location_ids[]" id="location_ids" class="form-select select2-modal" multiple required>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->name }} (Radius: {{ $loc->radius }}m)</option>
                                    @endforeach
                                </select>
                                <div class="form-text text-danger small">* Jika Free lokasi Absent Edit Pada Employee .</div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Keterangan</label>
                                <textarea name="description" id="description" class="form-control" rows="2" placeholder="Alasan mutasi..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">Proses Mutasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // DataTable
            $('#tblMutation').DataTable();

            // Select2 In Modal
            $('.select2-modal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#modalMutation'),
                width: '100%'
            });

            // Notifikasi
            @if (session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
            @endif

            // --- Tombol Add ---
            $('#btnCreate').on('click', function() {
                $('#formMutation')[0].reset();
                $('#methodField').html('');
                $('#modalTitle').text('Form Mutasi Karyawan Baru');
                $('#btnSubmit').text('Simpan Mutasi');
                $('.select2-modal').val(null).trigger('change');
                $('#formMutation').attr('action', "{{ route('mutation.store') }}");
                $('#modalMutation').modal('show');
            });

            // --- Tombol Edit ---
            $(document).on('click', '.btnEdit', function() {
                const id = $(this).data('id');
                const employeeId = $(this).data('employee-id');
                const locations = $(this).data('locations');

                $('#modalTitle').text('Edit Riwayat Mutasi');
                $('#btnSubmit').text('Update Mutasi');
                $('#methodField').html('@method("PUT")');

                // Fill inputs
                $('#employee_id').val(employeeId).trigger('change');
                $('#mutation_date').val($(this).data('date'));
                $('#new_branch_id').val($(this).data('new-branch')).trigger('change');
                $('#new_position_id').val($(this).data('new-position')).trigger('change');
                $('#description').val($(this).data('desc'));
                $('#location_ids').val(locations).trigger('change');

                $('#formMutation').attr('action', "{{ url('mutation/update') }}/" + id);
                $('#modalMutation').modal('show');
            });

            // --- Konfirmasi Hapus ---
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const form = $(this).closest('.form-delete');
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Riwayat mutasi akan dihapus, namun posisi terakhir karyawan tidak akan berubah otomatis.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
