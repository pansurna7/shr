<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Rekap Presensi Matrix - {{ $periode_teks }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        /* Pengaturan Kertas F4 Landscape */
        @page { size: F4 landscape }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 8px; /* Ukuran font diperkecil agar kolom tgl muat */
            color: #333;
        }

        .header-container {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .header-container h2 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header-container p { margin: 2px 0; font-size: 10px; }

        .tabel-rekap td {
            padding: 2px 0; /* Perkecil padding atas bawah */
            height: 25px;   /* Berikan tinggi tetap yang cukup untuk 2 baris teks */
            vertical-align: middle;
        }

        .tabel-rekap th, .tabel-rekap td {
            border: 1px solid #444;
            text-align: center;
            padding: 3px 1px;
        }

        .tabel-rekap th {
            background-color: #f0f0f0 !important; /* Gunakan important untuk DomPDF */
            font-weight: bold;
        }

        /* Pengaturan Lebar Kolom */
        .col-nama { width: 18%; text-align: left !important; padding-left: 5px !important; font-size: 8px;white-space: normal; overflow: hidden; }
        .col-tgl {
            width: 2.3% !important;
            font-weight: bold;
            font-size: 6px !important; /* Perkecil font jam agar tidak berantakan */
        }
        .col-total { width: 1.5% !important; font-weight: bold; background-color: #f9f9f9; }

        /* Warna Status Status */
        .bg-libur { background-color: #ebebeb !important; color: #777; }
        .bg-izin { background-color: #fff3cd !important; font-weight: bold; }
        .bg-alpa { background-color: #f8d7da !important; color: #721c24; font-weight: bold; }

        .legend {
            margin-top: 10px;
            font-size: 8px;
        }
        .legend-item { display: inline-block; margin-right: 15px; }
    </style>
</head>

<body class="F4 landscape">

    <section class="sheet padding-10mm">
        <article>
            <div class="header-container">
                <h2>{{ $setting->name ?? 'PT. LEXADEV' }}</h2>
                <div style="font-weight: bold; font-size: 12px;">LAPORAN REKAPITULASI PRESENSI</div>
                <p>Periode: {{ $periode_teks }}</p>
            </div>

            <table class="tabel-rekap">
                <thead>
                    <tr>
                        <th rowspan="2" class="col-nama">NAMA KARYAWAN</th>
                        <th colspan="{{ iterator_count($period) }}">TANGGAL</th>
                        <th colspan="5">TOTAL</th>
                    </tr>
                    <tr>
                        @foreach ($period as $date)
                            <th class="col-tgl">{{ $date->format('d') }}</th>
                        @endforeach
                        <th class="col-total">H</th>
                        <th class="col-total">I</th>
                        <th class="col-total">S</th>
                        <th class="col-total">C</th>
                        <th class="col-total" style="color: red;">A</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $emp)
                    <tr>
                        <td class="col-nama"><strong>{{ $emp->first_name }} {{ $emp->last_name }}</strong></td>

                        @foreach ($period as $date)
                            @php
                                $tgl = $date->format('Y-m-d');
                                $hari_indo = $hari_indo_map[$date->format('l')];
                                $status = ''; $bg_class = '';

                                // 1. Cek Libur Nasional
                                if (in_array($tgl, $holidays)) {
                                    $status = 'LN'; $bg_class = 'bg-libur';
                                }
                                else {
                                    // 2. Cek Libur Shift (SF)
                                    // Pastikan menggunakan $emp->dept_id dari loop luar
                                    $shift = ($all_schedules->get($emp->dept_id) ?? collect([]))->where('days', $hari_indo)->first();
                                    if ($shift && trim(strtolower($shift->shift_name)) == 'libur') {
                                        $status = 'SF'; $bg_class = 'bg-libur';
                                    }
                                }

                                // 3. Cek Kehadiran (Prioritas jika masuk di hari libur tetap tampil jam)
                                $pres = ($all_presences->get($emp->id) ?? collect([]))->where('date', $tgl)->first();
                                // 3. Cek Kehadiran (Prioritas jika masuk di hari libur tetap tampil jam)
                                $pres = ($all_presences->get($emp->id) ?? collect([]))->where('date', $tgl)->first();
                                if ($pres) {
                                    // Format Jam Masuk
                                    $jam_in = date('H:i', strtotime($pres->time_in));

                                    // Format Jam Pulang (Cek jika ada)
                                    $jam_out = $pres->time_out ? date('H:i', strtotime($pres->time_out)) : '--:--';

                                    // Gabungkan status untuk ditampilkan
                                    $status = $jam_in . '<br>' . $jam_out;
                                    $bg_class = '';
                                }
                                // 4. Cek Izin/Sakit/Cuti/Alpa (Hanya jika status belum terisi LN/SF)
                                elseif ($status == '') {
                                    $sub = ($all_submissions->get($emp->id) ?? collect([]))->first(fn($q) => $tgl >= $q->date && $tgl <= $q->end_date);
                                    if ($sub) {
                                        if ($sub->condition == 1) $status = 'I';
                                        elseif (in_array($sub->condition, [2, 3])) $status = 'S';
                                        elseif ($sub->condition == 4) $status = 'C';
                                        $bg_class = 'bg-izin';
                                    } else {
                                        // Cek Alpa hanya untuk tanggal yang sudah lewat
                                        if ($tgl <= date('Y-m-d')) {
                                            $status = 'A'; $bg_class = 'bg-alpa';
                                        }
                                    }
                                }
                            @endphp
                            <td class="{{ $bg_class }}" style="line-height: 1.2;">{!! $status !!}</td>
                        @endforeach

                        {{-- Summary Total --}}
                        <td class="col-total">{{ $emp->hadir }}</td>
                        <td class="col-total">{{ $emp->izin }}</td>
                        <td class="col-total">{{ $emp->sakit }}</td>
                        <td class="col-total">{{ $emp->cuti }}</td>
                        <td class="col-total" style="color: red;">{{ $emp->alpa }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="legend">
                <strong>Keterangan Status:</strong><br>
                <span class="legend-item"><strong>LN:</strong> Libur Nasional</span>
                <span class="legend-item"><strong>SF:</strong> Shift Free (Libur)</span>
                <span class="legend-item"><strong>I:</strong> Izin</span>
                <span class="legend-item"><strong>S:</strong> Sakit</span>
                <span class="legend-item"><strong>C:</strong> Cuti</span>
                <span class="legend-item"><strong>A:</strong> Alpa (Tanpa Keterangan)</span>
                <span class="legend-item"><strong>Atas:</strong> Jam Masuk</span>
                <span class="legend-item"><strong>Bawah:</strong> Jam Pulang</span>
            </div>
        </article>
    </section>

</body>
</html>
