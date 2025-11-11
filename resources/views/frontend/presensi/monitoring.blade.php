@extends('backend.layouts.app')
@section('title','Monitoring')
@push('css')
<style>
.img-circle {
    /* Atur ukuran gambar (kunci agar lingkaran terlihat sempurna) */
    width: 60px;
    height: 60px;
    /* Penting: Pastikan lebar dan tinggi sama (square) */

    /* Properti utama untuk membuat lingkaran */
    border-radius: 50%;

    /* Opsi tambahan: Agar gambar tidak terdistorsi saat diubah ukurannya */
    object-fit: cover;

    /* Opsi tambahan: Border melingkar */
    border: 2px solid #ccc;
}
</style>
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Monitoring Presence</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"><i class="bx bx-user-circle"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </div>

    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="text" class="form-control border-start-0" id="tanggal" name="tanggal" placeholder="Tanggal Presensi">
                                    <span class="input-group-text"><i class="bx bxs-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblMonitoring">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Tanggal</th>
                            <th scope="col" class="text-center align-middle">Nama</th>
                            <th scope="col" class="text-center align-middle">Jabatan</th>
                            <th scope="col" class="text-center align-middle">Departement</th>
                            <th scope="col" class="text-center align-middle">IN</th>
                            <th scope="col" class="text-center align-middle">Foto</th>
                            <th scope="col" class="text-center align-middle">OUT</th>
                            <th scope="col" class="text-center align-middle">Foto</th>
                            <th scope="col" class="text-center align-middle">Status</th>
                            <th scope="col" class="text-center align-middle"></th>

                        </tr>
                    </thead>
                    <tbody id="load-presensi">
                        {{-- @if (count($presences)>0)
                            @foreach ($presences as $d )
                                    <tr>
                                    <th scope="row" class="align-middle text-start">{{ $loop->iteration }}</th>
                                    <td class="align-middle text-start">{{ $d->employee->nik }}</td>
                                    <td class="align-middle">{{ $d->employee->first_name ." " . $d->employee->last_name }}</td>
                                    <td class="align-middle">{{ $d->employee->position->name }}</td>
                                    <td class="align-middle">{{ $d->employee->position->departement->name }}</td>
                                    <td class="align-middle text-start">{{ date('H:i:s',strtotime($d->time_in)) }}</td>
                                    <td class="align-middle text-center"><img class="img-circle" src="{{ asset('storage/absensi/'. $d->photo_in) }}"
                                        alt="Foto IN">
                                    </td>
                                    <td class="align-middle text-start">{{ $d->time_out }}</td>
                                    <td class="align-middle text-center"><img class="img-circle" src="{{ asset('storage/absensi/'. $d->photo_out) }}"
                                        alt="Foto Out">
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No Data Found!</td>
                            </tr>
                        @endif --}}
                    </tbody>
                </table>
                {{-- {{ $roles->links() }} --}}
            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-location" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white"Employee Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-white" id="loadMap">

                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>

        flatpickr("#tanggal", {
            mode: "range",
            dateFormat: "d-m-Y", // Format tanggal
            defaultDate: new Date(),
            // Menetapkan event handler
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    let tanggalAwal = dateStr.split(' to ')[0];
                    let tanggalAkhir = dateStr.split(' to ')[1];

                    // console.log("Tanggal Awal:", tanggalAwal);
                    // console.log("Tanggal Akhir:", tanggalAkhir);
                    // alert("Range Tanggal: " + dateStr);
                } else {
                    // Ketika hanya satu tanggal yang dipilih
                    console.log("Tanggal Tunggal Dipilih:", dateStr);
                }

                loadPresensi();

            },
        });
        function loadPresensi()
        {
            let dateStr = $("#tanggal").val();
            $.ajax({
                type    : "POST",
                url     : "/getpresensi",
                data    :   {
                                _token  : "{{ csrf_token() }}",
                                tanggal : dateStr
                            },
                // 🔥 PERBAIKAN: Perbaiki ejaan dan tambahkan koma (,)
                cache   : false,
                success : function (response) {
                    $("#load-presensi").html(response);
                },
                // Tambahkan error handler untuk debugging
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error, xhr.responseText);
                }
            });
        }
        loadPresensi();
        new DataTable('#tblMonitoring',{
            searching : false,
            lengthChange: false,
            pageLength: 500,
            columnDefs:[
                            {targets:[0,1,2,3,4,5,6,7,8,9], orderable:false},
                            // {targets:6, orderable:false},
                        ]

        });

        

    </script>
@endpush
