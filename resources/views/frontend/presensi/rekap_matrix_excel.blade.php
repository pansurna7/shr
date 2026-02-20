@php
    function selisih($jam_masuk, $jam_keluar) {
        if (!$jam_masuk || !$jam_keluar) return "0:0";
        $awal  = strtotime($jam_masuk);
        $akhir = strtotime($jam_keluar);
        $diff  = $akhir - $awal;
        $jam   = floor($diff / (60 * 60));
        $menit = ($diff - $jam * (60 * 60)) / 60;
        return $jam . ":" . round($menit);
    }
@endphp

<table border="1">
    <thead>
        <tr>
            <th colspan="{{ iterator_count($period) + 7 }}" style="font-weight: bold; text-align: center;">
                REKAPITULASI PRESENSI KARYAWAN
            </th>
        </tr>
        <tr>
            <th colspan="{{ iterator_count($period) + 7 }}" style="text-align: center;">
                Periode: {{ $periode_teks }}
            </th>
        </tr>
        <tr></tr>
        <tr style="background-color: #f0f0f0;">
            <th rowspan="2" style="vertical-align: middle;">NIK</th>
            <th rowspan="2" style="vertical-align: middle;">NAMA KARYAWAN</th>
            <th colspan="{{ iterator_count($period) }}">TANGGAL</th>
            <th colspan="5">TOTAL</th>
        </tr>
        <tr style="background-color: #f0f0f0;">
            @foreach ($period as $date)
                <th>{{ $date->format('d') }}</th>
            @endforeach
            <th>H</th>
            <th>I</th>
            <th>S</th>
            <th>C</th>
            <th style="color: red;">A</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rekap as $emp)
        <tr>
            <td>'{{ $emp->nik }}</td> {{-- Tanda kutip satu agar NIK tidak jadi scientific number --}}
            <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>

            @foreach ($period as $date)
                @php
                    $tgl = $date->format('Y-m-d');
                    $hari_indo = $hari_indo_map[$date->format('l')];
                    $status = ''; $bg = '';

                    // 1. Cek Libur
                    if (in_array($tgl, $holidays)) { $status = 'LN'; $bg = '#ebebeb'; }
                    else {
                        $shift = ($all_schedules->get($emp->dept_id) ?? collect([]))->where('days', $hari_indo)->first();
                        if ($shift && trim(strtolower($shift->shift_name)) == 'libur') { $status = 'SF'; $bg = '#ebebeb'; }
                    }

                    // 2. Kehadiran
                    $pres = ($all_presences->get($emp->id) ?? collect([]))->where('date', $tgl)->first();
                    if ($pres) {
                        $in = date('H:i', strtotime($pres->time_in));
                        $out = $pres->time_out ? date('H:i', strtotime($pres->time_out)) : '--:--';
                        $status = $in . " - " . $out;
                        $bg = '';
                    }
                    // 3. Izin/Alpa
                    elseif ($status == '') {
                        $sub = ($all_submissions->get($emp->id) ?? collect([]))->first(fn($q) => $tgl >= $q->date && $tgl <= $q->end_date);
                        if ($sub) {
                            if ($sub->condition == 1) $status = 'I';
                            elseif (in_array($sub->condition, [2, 3])) $status = 'S';
                            elseif ($sub->condition == 4) $status = 'C';
                            $bg = '#fff3cd';
                        } else {
                            if ($tgl <= date('Y-m-d')) { $status = 'A'; $bg = '#f8d7da'; }
                        }
                    }
                @endphp
                <td style="background-color: {{ $bg }}; text-align: center;">{{ $status }}</td>
            @endforeach

            <td style="text-align: center;">{{ $emp->hadir }}</td>
            <td style="text-align: center;">{{ $emp->izin }}</td>
            <td style="text-align: center;">{{ $emp->sakit }}</td>
            <td style="text-align: center;">{{ $emp->cuti }}</td>
            <td style="text-align: center; color: red;">{{ $emp->alpa }}</td>
        </tr>
        @endforeach
    </tbody>
    {{-- BARIS KOSONG SEBAGAI PEMISAH --}}
    <tr>
        <td colspan="{{ iterator_count($period) + 7 }}" style="border: none;"></td>
    </tr>

    {{-- BAGIAN KETERANGAN --}}
    <tr>
        <td colspan="3" style="font-weight: bold; border: none; text-align: left;">Keterangan Status:</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>LN:</strong> Libur Nasional</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>SF:</strong> Shift Free (Libur)</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>I:</strong> Izin</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>S:</strong> Sakit</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>C:</strong> Cuti</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="3" style="border: none; text-align: left;"><strong>A:</strong> Alpa (Tanpa Keterangan)</td>
        <td colspan="{{ iterator_count($period) + 4 }}" style="border: none;"></td>
    </tr>
    <tr>
        <td colspan="5" style="border: none; text-align: left;"><strong>HH:mm - HH:mm:</strong> Jam Masuk - Jam Pulang</td>
        <td colspan="{{ iterator_count($period) + 2 }}" style="border: none;"></td>
    </tr>
</table>
