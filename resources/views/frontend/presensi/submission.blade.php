@extends('backend.layouts.app')
@section('title','Submission')
@push('css')

    <style>
        .img-circle {
            /* Atur ukuran gambar (kunci agar lingkaran terlihat sempurna) */
            width: 80px;
            height: 60px;
            /* Penting: Pastikan lebar dan tinggi sama (square) */

            /* Properti utama untuk membuat lingkaran */
            /* border-radius: 50%; */

            /* Opsi tambahan: Agar gambar tidak terdistorsi saat diubah ukurannya */
            object-fit: cover;

            /* Opsi tambahan: Border melingkar */
            /* border: 2px solid #ccc; */
        }
        /* Gambar di dalam tabel */
        .zoomable-image {
            cursor: pointer; /* Menunjukkan bahwa gambar dapat diklik */
            transition: 0.3s;
            width: 50px; /* Ukuran default gambar di tabel */
            height: 50px;
            object-fit: cover;
            border-radius: 5px; /* Sedikit border-radius agar lebih halus */
        }

        .zoomable-image:hover {
            opacity: 0.7; /* Sedikit efek saat dihover */
        }

        /* Modal Overlay */
        .modal {
            display: none; /* Sembunyikan secara default */
            position: fixed; /* Tetap di viewport */
            z-index: 1000; /* Di atas semua elemen lain */
            padding-top: 50px; /* Posisi konten dari atas */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Aktifkan scroll jika konten terlalu besar */
            background-color: rgba(0,0,0,0.9); /* Latar belakang gelap */
        }

        /* Konten Modal (Gambar) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        /* Teks Caption Modal */
        .caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Tombol Tutup */
        .close-button {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
            cursor: pointer;
        }

        .close-button:hover,
        .close-button:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Animasi Zoom */
        @keyframes zoom {
            from {transform: scale(0.1)}
            to {transform: scale(1)}
        }

        @-webkit-keyframes zoom {
            from {-webkit-transform: scale(0.1)}
            to {-webkit-transform: scale(1)}
        }

        /* Responsif untuk layar kecil */
        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Submissions List</div>
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
                    <div class="table-responsive table-bordered">
                <table class="table mb-0" id="tblSubmission">
                    <thead class="table-light text-bold">
                        <tr>
                            <th scope="col" class="text-center align-middle">#</th>
                            <th scope="col" class="text-center align-middle">Tanggal</th>
                            <th scope="col" class="text-center align-middle">Nama</th>
                            <th scope="col" class="text-center align-middle">Jabatan</th>
                            <th scope="col" class="text-center align-middle">Submission</th>
                            <th scope="col" class="text-center align-middle">Ket</th>
                            <th scope="col" class="text-center align-middle">Foto</th>
                            <th scope="col" class="text-center align-middle">Status</th>
                            <th scope="col" class="text-center align-middle">Action</th>

                        </tr>
                    </thead>
                    <tbody id="load-presensi">
                        @if (count($submissions)>0)
                            @foreach ($submissions as $d )
                                <tr>
                                    <th scope="row" class="align-middle text-start">{{ $loop->iteration }}</th>
                                    <td class="align-middle text-start">{{ date('d-m-Y',strtotime($d->date)) }}</td>
                                    <td class="align-middle">{{ $d->employee->first_name ." " . $d->employee->last_name }}</td>
                                    <td class="align-middle">{{ $d->employee->position->name }}</td>
                                    </td>
                                    <td class="align-middle">
                                        {{
                                            $d->condition == 1 ? 'Izin' : (
                                                $d->condition == 2 ? 'Sakit (Tanpa Surat Dokter)' : (
                                                    $d->condition == 3 ? 'Sakit (Dengan Surat Dokter)' : 'Lain-lain'
                                                )
                                            )
                                        }}
                                    </td>
                                    <td class="align-middle">{{ $d->information }} </td>
                                    <td class="align-middle text-center">
                                        <img class="img-circle zoomable-image" src="{{ $d->photo != null ? asset('storage/'. $d->photo) : " " }}" alt="Foto Surat Sakit">
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $status = $d->status;
                                            $text = '';
                                            $class = '';

                                            if ($status == 0) {
                                                $text = "Waiting";
                                                $class = "bg-warning text-dark"; // Kuning untuk Waiting
                                            } elseif ($status == 1) {
                                                $text = "Approve";
                                                $class = "bg-success"; // Hijau untuk Approve
                                            } else {
                                                $text = "Reject";
                                                $class = "bg-danger"; // Merah untuk Reject
                                            }
                                        @endphp

                                        {{-- Gunakan Span dengan kelas badge Bootstrap --}}
                                        <span class="badge {{ $class }}">
                                            {{ $text }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @if ($d->status == 0)
                                            {{-- Tombol Approve (Status = 1) --}}
                                            <form action="{{ route('submissions.update_status', $d->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="1">
                                                @can('submissions.approv')
                                                    <button type="submit" class="btn btn-sm btn-success m-1">
                                                        Approve
                                                    </button>
                                                @endcan
                                            </form>

                                            {{-- Tombol Reject (Status = 2) --}}
                                            <form action="{{ route('submissions.update_status', $d->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="2">
                                                @can('submissions.approv')
                                                    <button type="submit" class="btn btn-sm btn-danger m-1">
                                                        Reject
                                                    </button>
                                                @endcan
                                            </form>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-success m-1" disabled>
                                                    Approve
                                            </button>
                                            {{-- Tombol Reject (Status = 2) --}}
                                            <button type="submit" class="btn btn-sm btn-danger m-1" disabled>
                                                    Reject
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No Data Found!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{-- modal untuk zoom photo --}}
                <div id="imageModal" class="modal">
                    <span class="close-button">&times;</span>
                    <img class="modal-content" id="img01">
                    <div id="caption" class="caption"></div>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-location" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Presence Location</h5>
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
        new DataTable('#tblSubmission');
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil elemen modal
            var modal = document.getElementById("imageModal");

            // Ambil gambar di dalam modal
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");

            // Dapatkan semua gambar yang dapat di-zoom
            var images = document.querySelectorAll('.zoomable-image');

            // Loop melalui setiap gambar dan tambahkan event listener
            images.forEach(function(img) {
                img.onclick = function() {
                    modal.style.display = "block";
                    // Gunakan data-src-full jika ada, jika tidak, gunakan src default
                    modalImg.src = this.getAttribute('data-src-full') || this.src;
                    captionText.innerHTML = this.alt; // Menampilkan teks alt sebagai caption
                }
            });

            // Dapatkan tombol tutup (X)
            var span = document.getElementsByClassName("close-button")[0];

            // Saat pengguna mengklik (x), tutup modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // Juga tutup modal jika mengklik di luar gambar
            modal.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
@endpush
