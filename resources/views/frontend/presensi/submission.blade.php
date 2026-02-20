@extends('backend.layouts.app')
@section('title', 'Submission')

@push('css')
    <style>
        .img-circle {
            width: 50px;
            height: 50px;
            object-fit: cover;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Modal Overlay */
        .modal-zoom {
            display: none;
            position: fixed;
            z-index: 9999;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-zoom-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            animation: zoom 0.3s;
        }

        @keyframes zoom {
            from {
                transform: scale(0.1)
            }

            to {
                transform: scale(1)
            }
        }

        .close-zoom {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Submissions List</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-user-circle"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </div>

    <hr />
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle" id="tblSubmission">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Diinput Pada</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Nama</th>
                            <th>Pengajuan</th>
                            <th>Ket</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($d->created_at)
                                        @php $tglInput = \Carbon\Carbon::parse($d->created_at); @endphp
                                        <span>{{ $tglInput->format('d/m/Y') }}</span><br>
                                        <small class="text-muted">{{ $tglInput->diffForHumans() }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    {{ date('d-m-Y', strtotime($d->date)) }}
                                    @if ($d->end_date && $d->end_date != $d->date)
                                        <br><small>s/d {{ date('d-m-Y', strtotime($d->end_date)) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $d->employee->first_name }} {{ $d->employee->last_name }}</strong>
                                    <small class="text-muted d-block"><i class="bx bx-briefcase"></i>
                                        {{ $d->employee->position->name }}</small>
                                </td>
                                <td>
                                    @if ($d->condition == 1)
                                        <span class="badge bg-primary">Izin</span>
                                    @elseif($d->condition == 2)
                                        <span class="badge bg-danger">Sakit (Tanpa Surat)</span>
                                    @elseif($d->condition == 3)
                                        <span class="badge bg-warning text-dark">Sakit (Surat Dokter)</span>
                                    @elseif($d->condition == 5)
                                        <span class="badge bg-dark">Koreksi Presensi</span>
                                    @elseif($d->condition == 4)
                                        <span class="badge bg-info">Cuti</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->condition == 5)
                                        <div class="fw-bold">{{ $d->information }}</div>
                                        <small class="text-muted">In: {{ $d->jam_in_pengajuan ?? '-' }} | Out:
                                            {{ $d->jam_out_pengajuan ?? '-' }}</small>
                                    @else
                                        {{ $d->information }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($d->photo)
                                        <img class="img-circle zoomable-image" src="{{ asset('storage/' . $d->photo) }}"
                                            alt="Lampiran">
                                    @endif
                                </td>
                                <td>
                                    @if ($d->status == 0)
                                        <span class="badge bg-warning text-dark">Waiting</span>
                                    @elseif($d->status == 1)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->status == 0)
                                        @can('submissions.approv')
                                            <form action="{{ route('submissions.update_status', $d->id) }}" method="POST"
                                                class="d-inline form-status">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="1">
                                                <button type="button" class="btn btn-sm btn-success btn-action"
                                                    data-type="Approve">Approve</button>
                                            </form>
                                            <form action="{{ route('submissions.update_status', $d->id) }}" method="POST"
                                                class="d-inline form-status">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="2">
                                                <button type="button" class="btn btn-sm btn-danger btn-action"
                                                    data-type="Reject">Reject</button>
                                            </form>
                                        @endcan
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>Selesai</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Zoom --}}
    <div id="imageModal" class="modal-zoom">
        <span class="close-zoom">&times;</span>
        <img class="modal-zoom-content" id="imgFull">
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#tblSubmission').DataTable();

            // SWAL untuk Approve/Reject
            $('.btn-action').on('click', function() {
                let type = $(this).data('type');
                let form = $(this).closest('form');

                Swal.fire({
                    title: 'Konfirmasi ' + type,
                    text: "Apakah Anda yakin ingin " + type.toLowerCase() + " pengajuan ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: type === 'Approve' ? '#198754' : '#dc3545',
                    confirmButtonText: 'Ya, ' + type + '!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // Zoom Image Logic
            $('.zoomable-image').on('click', function() {
                $('#imageModal').fadeIn();
                $('#imgFull').attr('src', $(this).attr('src'));
            });

            $('.close-zoom, #imageModal').on('click', function() {
                $('#imageModal').fadeOut();
            });

            // Sukses Notif
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
@endpush
