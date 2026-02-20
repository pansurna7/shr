<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekap</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page { size: F4 }


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

        .tabel-rekap {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        font-size: 12px; /* Font lebih kecil untuk memuat banyak kolom */
        }

        /* Pengaturan Umum untuk semua cell */
        .tabel-rekap th,
        .tabel-rekap td {
            border: 1px solid #ccc;
            text-align: center;
            padding: 5px 2px; /* Padding vertikal 5px, horizontal 2px (lebih rapat) */
            white-space: nowrap; /* Penting: Mencegah data memotong baris */
        }

        /* Header Columns */
        .tabel-rekap th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        /* Kolom NIK dan NAMA */
        .tabel-rekap th:nth-child(1),
        .tabel-rekap td:nth-child(1) {
            width: 60px; /* Lebar tetap untuk NIK */
            padding: 5px 8px;
        }

        .tabel-rekap th:nth-child(2),
        .tabel-rekap td:nth-child(2) {
            width: 100px; /* Lebar tetap untuk Nama */
            text-align: left; /* Nama sebaiknya rata kiri */
            padding: 5px 8px;
        }

        /* Kolom Tanggal (1 hingga 31) */
        /* Selector ini menargetkan kolom ketiga dan seterusnya */
        .tabel-rekap th:nth-child(n+3),
        .tabel-rekap td:nth-child(n+3) {
            /* ✅ KUNCI: Buat kolom sangat sempit dan seragam */
            width: 40px;
            min-width: 40px;
            max-width: 80px;
            padding: 5px 0px; /* Padding horizontal hampir nol */
            /* Sembunyikan jika teks tetap terlalu lebar */
        }

        /* Baris Data (untuk jam masuk-keluar) */
        .tabel-rekap td {
            /* Pastikan teks jam/data di tengah */
            font-size: 10px; /* Ukuran font lebih kecil untuk data jam (HH:MM-HH:MM) */
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


        /* 1. Kondisi Terlambat Saja */
        .late {
            background-color: #fdd; /* Merah muda terang */
            color: #c00; /* Teks merah gelap */
            font-weight: bold;
        }

        /* 2. Kondisi Pulang Lebih Cepat Saja */
        .early-out {
            background-color: #ffe0b2; /* Oranye muda terang */
            color: #e65100; /* Teks oranye gelap */
        }

        /* 3. Kondisi Terlambat DAN Pulang Lebih Cepat (Jika perlu pembedaan) */
        .late-and-early {
            background-color: #f0c4e8; /* Ungu/Magenta muda */
            color: #880e4f; /* Teks ungu gelap */
            font-weight: bold;
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
<body class="F4 landscape">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <!-- Write HTML just like a web page -->
        <article>

            <div class="header-container">
                <img src="{{ asset('storage/' .$setting->logo) }}" class="logo-icon" alt="logo icon" width="70" height="70">
                <h1>PT. LEXADEV</p></h1>

                <h1>REKAP PRESENSI KARYAWAN</h1>
                <h2>PERIODE NOVEMBER TAHUN 2025</h2>

                {{-- <p>PT. LEXADEV</p> --}}
                <p><i>Jln. Cakrabuana Blok K19 No.9 Kunciran Indah Tangerang Banten</i></p>
                <hr class="double-line-hr">
            </div>

            <table class="tabel-rekap">
                <tr>
                    <th rowspan="2">NIK</th>
                    <th rowspan="2">Nama</th>
                    <th colspan="31">Tanggal</th>
                    <th rowspan="2">TH</th>
                    <th rowspan="2">TT</th>
                </tr>
                <tr>
                    @for ($i=1; $i<=31; $i++)
                        <th>{{$i}}</th>
                    @endfor
                </tr>
                @foreach ($presences as $d)
                    <tr>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->name }}</td>

                        {{-- 1. INISIALISASI COUNTER PER KARYAWAN --}}
                        @php
                            $total_hadir = 0;
                            $total_terlambat = 0;
                            $total_pulang_cepat = 0; // Opsional: Tambahkan counter PC
                        @endphp

                        @for ($i = 1; $i <= 31; $i++)
                            @php
                                $tgl = "tgl_" . $i;
                                $data_full = $d->$tgl;

                                // --- Inisialisasi status harian ---
                                $formatted_jam = '';
                                $is_late = false;
                                $is_early_out = false;
                                $is_present = false; // Status hadir/absen
                                $cell_class = '';

                                $jam_masuk_standar = $d->entry_time;
                                $jam_pulang_standar = $d->out_time;

                                $time_in_full = '';
                                $time_out_full = '';
                                $separator_pos = strpos($data_full, ' - ');

                                // --- 2. Ekstraksi dan Format Jam ---
                                if (!empty($data_full)) {
                                    $is_present = true; // Jika ada data jam, dianggap hadir

                                    if ($separator_pos !== false) {
                                        // IN dan OUT
                                        $time_in_full = substr($data_full, 0, $separator_pos);
                                        $time_out_full = substr($data_full, $separator_pos + 3);
                                        $formatted_jam = substr($time_in_full, 0, 5) . ' - ' . substr($time_out_full, 0, 5);
                                    } else {
                                        // Hanya IN
                                        $time_in_full = $data_full;
                                        $formatted_jam = substr($time_in_full, 0, 5);
                                    }
                                }

                                // --- 3. LOGIKA PENENTUAN STATUS (Jika ada data) ---
                                if ($is_present) {
                                    // Cek Terlambat (jika ada jam masuk dan > standar)
                                    if (strtotime($time_in_full) > strtotime($jam_masuk_standar)) {
                                        $is_late = true;
                                    }

                                    // Cek Pulang Cepat (jika ada jam keluar dan < standar)
                                    if (!empty($time_out_full) && strtotime($time_out_full) < strtotime($jam_pulang_standar)) {
                                        $is_early_out = true;
                                    }
                                }

                                // --- 4. INCREMENT COUNTERS ---
                                if ($is_present) {
                                    $total_hadir++; // Menghitung hari hadir
                                }

                                if ($is_late) {
                                    $total_terlambat++; // Menghitung hari terlambat
                                }

                                if ($is_early_out) {
                                    $total_pulang_cepat++; // Menghitung hari pulang cepat
                                }

                                // --- 5. PENERAPAN CLASS ---
                                if ($is_late && $is_early_out) {
                                    $cell_class = 'late-and-early';
                                } elseif ($is_late) {
                                    $cell_class = 'late';
                                } elseif ($is_early_out) {
                                    $cell_class = 'early-out';
                                }

                            @endphp

                            <td class="{{ $cell_class }}">
                                {{ $formatted_jam }}
                            </td>
                        @endfor

                        {{-- 5. TAMBAHKAN KOLOM TOTAL SETELAH LOOP TANGGAL --}}
                        <td class="total-column">{{ $total_hadir }}</td>
                        <td class="total-column">{{ $total_terlambat }}</td>
                        {{-- <td class="total-column">{{ $total_pulang_cepat }}</td> --}}

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
                    <td class="ttd-kanan">
                        <strong>Surya Panggabean</strong><br>
                        <span class="jabatan">Direktur</span>
                    </td>
                    <td class="ttd-kiri">
                        <strong>Mei Pardede</strong><br>
                        <span class="jabatan">HRD Manager</span>
                    </td>
                </tr>
            </table>

        </article>

    </section>

</body>

</html>
