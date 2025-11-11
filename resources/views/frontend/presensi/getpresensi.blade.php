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
@if (count($presences)>0)
    @foreach ($presences as $d )
        <tr>
            <th scope="row" class="align-middle text-start">{{ $loop->iteration }}</th>
            <td class="align-middle text-start">{{ date('d-m-Y', strtotime($d->date)) }}</td>
            <td class="align-middle">{{ $d->employee->first_name ." " . $d->employee->last_name }}</td>
            <td class="align-middle">{{ $d->employee->position->name }}</td>
            <td class="align-middle">{{ $d->employee->position->departement->name }}</td>
            <td class="align-middle text-start">
                {!! $d->time_in != null
                    ? date('H:i:s', strtotime($d->time_in))
                    : "<span class='badge bg-danger'>Tidak Absen</span>"
                !!}
            </td>
            <td class="align-middle text-center"><img class="img-circle" src="{{ $d->photo_in != null ? asset('storage/absensi/'. $d->photo_in) : " " }}"
                alt="Foto IN">
            </td>
            <td class="align-middle text-start">
                {!! $d->time_out != null
                    ? date('H:i:s', strtotime($d->time_out))
                    : "<span class='badge bg-danger'>Belum Absen Pulang</span>"
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
                        <i class="bx bx-loader-circle"></i>
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
            <td class="align-middle text-center">
                {{-- @can('position.edit') --}}
                    <a href="#" class="btn btn-primary" id="showMap">
                        <i class="bx bx-map-pin"></i>
                    </a>
                {{-- @endcan --}}
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="7" class="text-center">No Data Found!</td>
    </tr>
@endif

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#showMap").click(function (e) {
            // e.preventDefault();
                alert('ok');
            });
        });

    </script>
@endpush
