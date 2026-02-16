@extends('backend.layouts.app')
@section('title','Dashboard')
@section('content')

<div class="row mt-2">
    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #0d6efd !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="document-text-outline" class="text-primary me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Izin</h6>
                        <h4 class="mb-0 fw-bold">{{ $rekap_izin->jml_izin ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #b53b30 !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="medkit-outline" class="text-danger me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Sakit</h6>
                        <h4 class="mb-0 fw-bold">{{ ($rekap_izin->jml_sakit ?? 0) + ($rekap_izin->jml_sakit_dokter ?? 0) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #198754 !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="calendar-outline" class="text-success me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Cuti</h6>
                        <h4 class="mb-0 fw-bold">{{ $rekap_izin->jml_cuti ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #198754 !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="finger-print-outline" class="text-success me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Hadir</h6>
                        <h4 class="mb-0 fw-bold">{{ $rekap_presensi->jml_hadir }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #6c757d !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="alert-circle-outline" class="text-danger me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Alpha/TP</h6>
                        <h4 class="mb-0 fw-bold">{{ $tidak_hadir }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm" style="border-left: 5px solid #ae9333 !important;">
            <div class="card-body p-2">
                <div class="d-flex align-items-center">
                    <ion-icon name="alarm-outline" class="text-warning me-2" style="font-size: 2rem;"></ion-icon>
                    <div>
                        <h6 class="mb-0 text-muted small">Terlambat</h6>
                        <h4 class="mb-0 fw-bold">{{  $rekap_presensi->jml_terlambat ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="px-2 mb-2">
        <small class="text-muted">Total Karyawan: <strong>{{ $total_karyawan }}</strong> | Hadir: <strong class="text-success">{{ $jml_hadir }}</strong></small>
    </div>
    <div class="card border-0 shadow-sm mt-3">
        <div class="card-header border-0 py-3">
            <h6 class="mb-0 fw-bold"><ion-icon name="people-outline" class="me-1"></ion-icon> Karyawan Absen Hari Ini</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tblAbsen">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Employee Name</th>
                            <th>Condition</th>
                            <th class="text-center">Periode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($daftar_izin_hari_ini as $row)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <div class="fw-bold">{{ $row->first_name }}</div>
                                </td>
                                <td>
                                    @if($row->condition == 1)
                                        <span class="badge bg-primary">Izin</span>
                                    @elseif($row->condition == 2)
                                        <span class="badge bg-danger">Sakit</span>
                                    @elseif($row->condition == 3)
                                        <span class="badge bg-warning text-dark">Sakit Dokter</span>
                                    @elseif($row->condition == 4)
                                    {{-- Asumsi kolom leave_id ada di tabel submissions --}}
                                    @if($row->leave_id == 1)
                                        <span class="badge bg-success">Cuti Tahunan</span>
                                    @elseif($row->leave_id == 2)
                                        <span class="badge bg-info">Cuti Melahirkan</span>
                                    @endif
                                @endif
                                </td>
                                <td class="text-center small text-muted">
                                    {{ date('d/m/y', strtotime($row->date)) }} - {{ date('d/m/y', strtotime($row->end_date)) }}
                                </td>
                            </tr>
                        @empty
                            {{-- <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <ion-icon name="happy-outline" style="font-size: 2rem;"></ion-icon>
                                    <p class="mb-0">Semua karyawan hadir hari ini!</p>
                                </td>
                            </tr> --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-0 py-3">
                    <h6 class="mb-0 fw-bold">Tren Kehadiran (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
@push('scripts')
    <script>
        new DataTable('#tblAbsen');
    </script>
    {{-- chart --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Hadir',
                        data: {!! json_encode($chartData['hadir']) !!},
                        borderColor: '#198754', // Hijau
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Izin/Sakit/Cuti',
                        data: {!! json_encode($chartData['absen']) !!},
                        borderColor: '#0d6efd', // Biru
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Alpha',
                        data: {!! json_encode($chartData['alpha']) !!},
                        borderColor: '#6c757d', // Abu-abu
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderDash: [5, 5] // Garis putus-putus untuk membedakan Alpha
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    </script>
    {{-- end chart  --}}
@endpush
