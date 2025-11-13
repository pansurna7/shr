<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Report</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page { size: A4 }


        .header-container {
            /* 1. Rata tengah teks dan elemen inline */
            text-align: center;
        }

        .header-logo {
            /* 2. Agar logo (elemen block) bisa rata tengah */
            display: block;
            margin: 0 auto;
            /* Sesuaikan lebar/tinggi logo jika perlu */
            width: 60px;
            height: 60px;
        }

        /* Opsional: Atur jarak antar baris */
        .header-container h1,
        .header-container h2,
        .header-container p {
            margin: 0; /* Hapus margin default */
        }

        .employee-details-table {
            width: 100%; /* Atau lebar spesifik, misal 600px */
            border-collapse: collapse; Menghilangkan spasi antar border cell
            margin: 20px auto; /* Memberi jarak dari atas/bawah dan rata tengah tabel */
        }

        .employee-details-table td {
            padding: 8px; /* Jarak padding default untuk semua cell */
            /* border: 1px solid #ccc; Border untuk tampilan tabel */
            vertical-align: top; /* Pastikan konten rata atas secara default */
        }

        .employee-photo-cell {
            width: 150px; /* Lebar tetap untuk kolom gambar */
            /* Opsional: Sesuaikan tinggi jika gambar seharusnya mengisi tinggi tabel */
            /* height: 200px; */
            text-align: center; /* Untuk meratakan gambar secara horizontal di dalam cell */
            vertical-align: middle; /* ✅ Untuk meratakan gambar secara vertikal di dalam cell */
            padding: 0; /* Hapus padding jika gambar perlu rapat ke sisi cell */
        }

        .employee-photo {
            /* ✅ Ukuran Maksimal Gambar */
            max-width: 100%; /* Gambar akan mengisi lebar cell, tidak lebih */
            max-height: 200px; /* Tinggi maksimal gambar agar tidak terlalu besar */
            height: auto; /* Mempertahankan rasio aspek gambar */
            display: block; /* Agar margin auto bekerja jika diperlukan */
            margin: 0 auto; /* ✅ Meratakan gambar secara horizontal di dalam cell */
            object-fit: contain; /* Memastikan seluruh gambar terlihat dalam batas */
            border-radius: 5px; /* Opsional: sedikit sudut membulat */
        }

        /* Penyesuaian untuk cell teks di sebelah gambar */
        .detail-label, .detail-value {
            white-space: nowrap; /* Mencegah teks label dan value memotong baris */
        }

        .detail-label {
            font-weight: bold;
            width: 80px; /* Lebar tetap untuk kolom label */
        }

        .detail-separator {
            width: 15px; /* Lebar kecil untuk pemisah ':' */
            text-align: center;
        }

        .tabel-presensi {
            /* 1. Pengaturan Dasar Tabel */
            width: 100%; /* Lebar tabel 100% dari container */
            border-collapse: collapse; /* Menghilangkan spasi antar border cell */
            margin: 20px 0; /* Memberi jarak di atas dan bawah tabel */
            font-family: Arial, sans-serif; /* Font yang mudah dibaca */
            font-size: 14px;
        }

        .tabel-presensi th,
        .tabel-presensi td {
            /* 2. Padding dan Border Cell */
            padding: 10px; /* Jarak di dalam cell */
            border: 1px solid #ddd; /* Border tipis berwarna abu-abu */
            text-align: center; /* Rata tengah untuk semua konten cell */
        }

        .tabel-presensi th {
            /* 3. Pengaturan Header */
            background-color: #f2f2f2; /* Warna background abu-abu muda untuk header */
            color: #333; /* Warna teks header */
            font-weight: bold;
            text-transform: uppercase; /* Huruf kapital untuk header */
        }

        .tabel-presensi tr:nth-child(even) {
            /* 4. Zebra Striping (Warna selang-seling untuk baris) */
            background-color: #f9f9f9;
        }

        .tabel-presensi tr:hover {
            /* 5. Efek Hover */
            background-color: #f0f0f0;
        }

        /* Penyesuaian lebar kolom spesifik jika diperlukan */
        .tabel-presensi th:nth-child(2),
        .tabel-presensi td:nth-child(2) {
            width: 15%; /* Kolom Tanggal */
        }
        .tabel-presensi th:nth-child(9),
        .tabel-presensi td:nth-child(9) {
            text-align: left; /* Keterangan mungkin lebih baik rata kiri */
        }

        /* Tabel Tanda Tangan */
        .tanda-tangan-tabel {
            width: 100%;
            border-collapse: collapse;
            border: none;
            /* ✅ KUNCI: Atur jarak dari tabel presensi di atas */
            margin-top: 20px;
        }

        .tanda-tangan-tabel td {
            width: 50%;
            padding: 0;
            border: none;
            vertical-align: top;
        }

        /* Penempatan Tanggal */
        .tanggal-laporan {
            text-align: right;
            font-size: 14px;
            margin-bottom: 50px;
            /* Jarak yang kecil ke nama ttd di bawahnya */
        }

        /* Penempatan Teks Tanda Tangan */
        .ttd-kiri, .ttd-kanan {
            text-align: center;
            padding-top: 5px; /* Jarak dari garis/nama di atasnya */
        }

        .ttd-kiri-spacer {
            /* Kosongkan kolom kiri untuk membuat kolom kanan 50% penuh */
            width: 50%;
        }

        /*  Untuk Gambar didalam Tabel presensi */
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
    @php
    // Function Untuk Menghitung Selisih Jam
    function selisih($jam_masuk, $jam_keluar)
        {
            list($h, $m, $s) = explode(":", $jam_masuk);
            $dtAwal = mktime($h, $m, $s, "1", "1", "1");
            list($h, $m, $s) = explode(":", $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode(".", $totalmenit / 60);
            $sisamenit = ($totalmenit / 60) - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ":" . round($sisamenit2);
        }
@endphp
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <!-- Write HTML just like a web page -->
        <article>
            {{-- <table style="width: 100%;">
                <tr>
                    <td>
                        <div style="width: 55px">
                            <img src="{{ asset('storage/' .$setting->logo) }}" class="logo-icon" alt="logo icon" width="70" height="70">
                        </div>
                    </td>
                    <td>
                        <span id="title">
                            LAPORAN PRESENSI KARYAWAN <br>
                            PERIODE {{$nama_bulan[$bulan]}} Tahun {{$tahun}} <br>
                            {{$setting->name}} <br>
                        </span>
                            <span><i>Jln. Cakrabuana Blok K19 No.9 Kunciran Indah Tangerang Banten</i></span>
                    </td>
                </tr>
            </table> --}}
            <div class="header-container">
                <img src="{{ asset('storage/' .$setting->logo) }}" class="logo-icon" alt="logo icon" width="70" height="70">
                <h1>PT. LEXADEV</p></h1>

                <h1>LAPORAN PRESENSI KARYAWAN</h1>
                <h2>PERIODE NOVEMBER TAHUN 2025</h2>

                {{-- <p>PT. LEXADEV</p> --}}
                <p><i>Jln. Cakrabuana Blok K19 No.9 Kunciran Indah Tangerang Banten</i></p>
                <hr class="double-line-hr">
            </div>

            <table class="employee-details-table">
                <tr>
                    <td rowspan="5" class="employee-photo-cell">
                         <img src="{{ asset('storage/' .$employee->avatar) }}" class="logo-icon" alt="logo icon" width="200px" height="150px">
                    </td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{$employee->nik}}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{$employee->first_name}} {{$employee->last_name}}</td>
                </tr>
                <tr>
                    <td>Departement</td>
                    <td>:</td>
                    <td>{{$employee->position->departement->name}}</td>
                </tr>
                <tr>
                    <td>Position</td>
                    <td>:</td>
                    <td>{{$employee->position->name}}</td>
                </tr>
            </table>

            <table class="tabel-presensi">
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>IN</th>
                    <th>Foto</th>
                    <th>OUT</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                    <th>Jml Jam</th>
                </tr>
                @foreach ($presences as $d )
                    <tr>
                        <th scope="row" class="align-middle text-start">{{ $loop->iteration }}</th>
                        <td class="align-middle text-start">{{ date('d-m-Y', strtotime($d->date)) }}</td>
                        <td class="align-middle text-start">
                            {!! $d->time_in != null
                                ? date('H:i:s', strtotime($d->time_in))
                                : "<span class='badge bg-danger'>Tidak Absen</span>"
                            !!}
                        </td>
                        <td class="align-middle text-center">
                            @if ($d->photo_in)
                                {{-- Jika ada foto, tampilkan gambar --}}
                                <img class="img-circle"
                                    src="{{ asset('storage/absensi/' . $d->photo_in) }}"
                                alt="Foto Pulang">
                            @else
                                {{-- Jika tidak ada foto, tampilkan ikon (Menggunakan {!! !!} aman di sini) --}}
                                <img class="img-circle"
                                    src="{{ asset('assets/images/camera.png')}}"
                                alt="Foto Pulang">
                            @endif
                        </td>
                        <td class="align-middle text-start">
                            {!! $d->time_out != null
                                ? date('H:i:s', strtotime($d->time_out))
                                : "<span class='badge bg-danger'>Tidak Absen</span>"
                            !!}
                        </td>

                        <td class="align-middle text-center">
                            @if ($d->photo_out)
                                {{-- Jika ada foto, tampilkan gambar --}}
                                <img class="img-circle"
                                    src="{{ asset('storage/absensi/' . $d->photo_out) }}"
                                alt="Foto Pulang">
                            @else
                                {{-- Jika tidak ada foto, tampilkan ikon (Menggunakan {!! !!} aman di sini) --}}
                                <img class="img-circle"
                                    src="{{ asset('assets/images/camera.png')}}"
                                alt="Foto Pulang">
                            @endif
                        </td>

                        <td class="align-middle text-center">
                            @if ($d->time_in >= "07:00")
                                @php
                                    $jam_terlambat = selisih("07:00:00", $d->time_in)
                                @endphp
                                <span class="badge bg-danger">Terlambat : {{$jam_terlambat}}</span>
                            @else
                                <span class="badge bg-success">Ontime</span>
                            @endif
                        </td>

                        <td>
                            @if ($d->time_out != null)
                                @php
                                    $jml_jam = selisih($d->time_in, $d->time_out)
                                @endphp
                            @else
                                @php
                                    $jml_jam = 0;
                                @endphp
                            @endif
                            {{$jml_jam}}
                        </td>

                    </tr>
                @endforeach
            </table>

            <table class="tanda-tangan-tabel">
    <tr>
        <td class="ttd-kiri-spacer"></td>
        <td class="tanggal-laporan">
            Tangerang, {{date('d-M-Y')}}
        </td>
    </tr>

    <tr>
        <td colspan="2" style="height: 100px;"></td>
    </tr>

    <tr>
        <td class="ttd-kiri">
            <strong>Mei Pardede</strong><br>
            <span class="jabatan">HRD Manager</span>
        </td>
        <td class="ttd-kanan">
            <strong>Surya Panggabean</strong><br>
            <span class="jabatan">Direktur</span>
        </td>
    </tr>
</table>

        </article>

    </section>

</body>

</html>
