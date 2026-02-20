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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Report Presensi - {{ $employee->first_name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page { size: A4 }
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header-container { text-align: center; margin-bottom: 20px; }
        .header-container h1 { margin: 0; font-size: 18px; }
        .header-container h2 { margin: 5px 0; font-size: 14px; text-transform: uppercase; }

        .employee-details-table { width: 100%; margin-bottom: 20px; border: 1px solid #eee; }
        .employee-details-table td { padding: 5px; }
        .employee-photo { width: 100px; height: 120px; object-fit: cover; border: 1px solid #ccc; }

        .tabel-presensi { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabel-presensi th, .tabel-presensi td { border: 1px solid #000; padding: 6px; text-align: center; }
        .tabel-presensi th { background-color: #f0f0f0; font-size: 11px; }

        .text-danger { color: #d9534f; font-weight: bold; }
        .text-success { color: #5cb85c; font-weight: bold; }
        .img-absensi { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }

        .tanda-tangan-tabel { width: 100%; margin-top: 30px; }
        .tanda-tangan-tabel td { text-align: center; border: none; }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-10mm">
        <article>
            <div class="header-container">
                <img src="{{ asset('storage/' .$setting->logo) }}" alt="logo" width="60">
                <h1>{{ $setting->name ?? 'PT. LEXADEV' }}</h1>
                <h2>LAPORAN PRESENSI PERIODE {{ $nama_bulan[$bulan] }} {{ $tahun }}</h2>
                <p><i>{{ $setting->address ?? 'Alamat Perusahaan' }}</i></p>
                <hr style="border: 1px double #000;">
            </div>

            <table class="employee-details-table">
                <tr>
                    <td rowspan="4" style="width: 120px; text-align: center;">
                        <img src="{{ $employee->avatar ? asset('storage/' . $employee->avatar) : asset('assets/images/user.png') }}" class="employee-photo">
                    </td>
                    <td style="width: 100px; font-weight: bold;">NIK</td>
                    <td>: {{ $employee->nik }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Nama</td>
                    <td>: {{ $employee->first_name }} {{ $employee->last_name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Jabatan</td>
                    <td>: {{ $employee->position->name }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Departemen</td>
                    <td>: {{ $employee->position->departement->name }}</td>
                </tr>
            </table>

            <table class="tabel-presensi">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Foto In</th>
                        <th>Jam Pulang</th>
                        <th>Foto Out</th>
                        <th>Keterangan</th>
                        <th>Jml Jam</th>
                    </tr>
                </thead>
                <tbody>
                    @php $grand_total_jam = 0; @endphp
                    @forelse ($presences as $d)
                        @php
                            $jml_jam = ($d->time_in && $d->time_out) ? selisih($d->time_in, $d->time_out) : "0:0";
                            // Pisahkan jam untuk total (opsional jika ingin menjumlahkan)
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ date('d-m-Y', strtotime($d->date)) }}</td>
                            <td>{{ $d->time_in ? date('H:i', strtotime($d->time_in)) : '-' }}</td>
                            <td>
                                <img src="{{ $d->photo_in ? asset('storage/absensi/'.$d->photo_in) : asset('assets/images/camera.png') }}" class="img-absensi">
                            </td>
                            <td>{{ $d->time_out ? date('H:i', strtotime($d->time_out)) : '-' }}</td>
                            <td>
                                <img src="{{ $d->photo_out ? asset('storage/absensi/'.$d->photo_out) : asset('assets/images/camera.png') }}" class="img-absensi">
                            </td>
                            <td>
                                @if ($d->time_in && $d->entry_time)
                                    @if ($d->time_in > $d->entry_time)
                                        <span class="text-danger">Terlambat ({{ selisih($d->entry_time, $d->time_in) }})</span>
                                    @else
                                        <span class="text-success">Ontime</span>
                                    @endif
                                @else
                                    <span class="text-danger">Pengajuan</span>
                                @endif
                            </td>
                            <td>{{ $jml_jam }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">Data presensi tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <table class="tanda-tangan-tabel">
                <tr>
                    <td style="width: 50%;"></td>
                    <td>Tangerang, {{ date('d F Y') }}</td>
                </tr>
                <tr style="height: 80px;">
                    <td>HRD Manager</td>
                    <td>Direktur</td>
                </tr>
                <tr>
                    <td><strong>( Mei Pardede )</strong></td>
                    <td><strong>( Surya Panggabean )</strong></td>
                </tr>
            </table>
        </article>
    </section>
</body>
</html>
