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
@forelse ($presences as $d)
    <tr>
        <th scope="row" class="align-middle text-start">{{ $loop->iteration }}</th>
        <td class="align-middle text-start">{{ date('d-m-Y', strtotime($d->date)) }}</td>
        <td class="align-middle">
            <div class="fw-bold">{{ $d->first_name ." " . $d->last_name }}</div>
            <small class="text-muted">{{ $d->position_name }}</small>
        </td>
        <td class="align-middle">{{ $d->departement_name }}</td>
        <td class="align-middle">
            <span class="badge bg-light text-dark border">{{ $d->tipe_jam_kerja ?? 'Pengajuan' }}</span>
        </td>

        {{-- JAM MASUK --}}
        <td class="align-middle">
            @if($d->time_in)
                <div class="fw-bold text-success">{{ date('H:i', strtotime($d->time_in)) }}</div>
            @else
                <span class="badge bg-danger">Tidak Absen</span>
            @endif
        </td>
        <td class="align-middle text-center">
            @if ($d->photo_in)
                <img class="img-circle zoomable-image"
                    src="{{ $d->photo_in != null ? asset('storage/absensi/'. $d->photo_in) : " "  }}"
                    alt="Foto Pulang"
                    style="width: 45px; height: 45px; cursor: pointer;">
            @else
                <i class="bx bx-camera-off text-muted font-20"></i>
            @endif
        </td>
        {{-- JAM PULANG --}}
        <td class="align-middle">
            @if($d->time_out)
                <div class="fw-bold text-primary">{{ date('H:i', strtotime($d->time_out)) }}</div>
            @else
                <span class="badge bg-warning text-dark small italic">Tidak Absen</span>
            @endif
        </td>

        {{-- FOTO PULANG (Contoh Zoomable) --}}
        <td class="align-middle text-center">
            @if ($d->photo_out)
                <img class="img-circle zoomable-image"
                    src="{{ $d->photo_out != null ? asset('storage/absensi/'. $d->photo_out) : " "  }}"
                    alt="Foto Pulang"
                    style="width: 45px; height: 45px; cursor: pointer;">
            @else
                <i class="bx bx-camera-off text-muted font-20"></i>
            @endif
        </td>

        {{-- STATUS KETERLAMBATAN --}}
        <td class="align-middle text-center">
            @if ($d->status == 'p')
                <span class="text-muted">Pengajuan</span>
            @else
                @if ($d->time_in && $d->entry_time)
                    @if ($d->time_in > $d->entry_time)
                        @php
                            // Hitung selisih jika fungsi selisih() tersedia,
                            // atau gunakan Carbon::parse($d->time_in)->diff(Carbon::parse($d->entry_time))->format('%H:%I:%S');
                            $jam_terlambat = selisih($d->entry_time, $d->time_in);
                        @endphp
                        <span class="badge bg-danger">Terlambat: {{ $jam_terlambat }}</span>
                    @else
                        <span class="badge bg-success">Tepat Waktu</span>
                    @endif
                @else
                    <span class="text-muted">-</span>
                @endif
            @endif
        </td>

        {{-- MAP PIN --}}
        <td class="align-middle text-center">
            <button class="btn btn-sm btn-outline-info showMap" id="{{ $d->id }}">
                <i class="bx bx-map-pin"></i>
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center p-4">
            <div class="text-muted">Tidak ada data presensi ditemukan untuk range tanggal ini.</div>
        </td>
    </tr>
@endforelse
<script>
    $(document).ready(function () {
       $("#modal-location").on('shown.bs.modal', function () {

            map.invalidateSize();

        });
        // Definisi token CSRF sebagai variabel aman
        const csrfToken = '{{ csrf_token() }}';

    // 1. Tangani Klik pada Elemen dengan Class 'showMap'
    $(".showMap").click(function (e) {
        e.preventDefault(); // Mencegah perilaku default jika ini adalah link

        // 2. Mendapatkan ID dari elemen yang DIKLIK (KONTEKS YANG BENAR)
        let id = $(this).attr("id");

        $.ajax({
            type    : "POST",
            url     : "/showmap",
            data    : {
                        _token  : csrfToken, // Menggunakan variabel yang sudah di-quote
                        id      : id,
                        },
            cache   : false,
            success: function (response) {
                console.log("ID yang dikirim:", id);

                // 3. Muat respons (misalnya peta) ke dalam modal atau elemen lain


                // Tampilkan modal setelah konten berhasil dimuat
                $("#modal-location").modal('show');
                $("#loadMap").html(response);
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

});
</script>
{{-- @endpush --}}
