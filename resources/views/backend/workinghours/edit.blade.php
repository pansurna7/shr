
<form id="form-edit-workinghour"  class="needs-validation" data-id="{{$workinghour->id}}" novalidate>
    @csrf
    <input type="hidden" name="_method" value="PUT">

    <div class="row mb-3">
        <label for="name" class="col-sm-3 col-form-label">Nama JK</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="name" name="name" value="{{$workinghour->name}}" required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="awaljm" class="col-sm-3 col-form-label">Awal Jam Masuk</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                {{-- ✅ Isi nilai awal dari database, format ke H:i:s --}}
                <input type="text" class="form-control time" id="awaljm" name="awaljm"
                    value="{{ $workinghour->start_time ? \Carbon\Carbon::parse($workinghour->start_time)->format('H:i:s') : '' }}"
                    required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="entry_time" class="col-sm-3 col-form-label">Jam Masuk</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control time" id="entry_time" name="entry_time"
                    value="{{ $workinghour->entry_time ? \Carbon\Carbon::parse($workinghour->entry_time)->format('H:i:s') : '' }}"
                    required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="end_time" class="col-sm-3 col-form-label">Akhir Jam Masuk</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control time" id="end_time" name="end_time"
                        value="{{ $workinghour->end_time ? \Carbon\Carbon::parse($workinghour->end_time)->format('H:i:s') : '' }}"
                        required>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="out_time" class="col-sm-3 col-form-label">Jam Pulang</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control time" id="out_time" name="out_time"
                    value="{{ $workinghour->out_time ? \Carbon\Carbon::parse($workinghour->out_time)->format('H:i:s') : '' }}">
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <div class="text-center mt-5">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="lni lni-arrow-left-circle"></i>Close</button>
            <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Save</button>
        </div>
    </div>
</form>

<script>

    // ✅ Menargetkan semua input dengan class 'time'
    flatpickr(".time", {
        // ✅ Sintaks Flatpickr sudah benar

        // Opsi utama untuk mengaktifkan pemilih waktu
        enableTime: true,

        // Opsi utama untuk menyembunyikan kalender tanggal
        noCalendar: true,

        // Opsi Tambahan
        dateFormat: "H:i:S", // Menggunakan S untuk detik (sesuai format database)
        time_24hr: true,    // Menggunakan format 24 jam

        // Menonaktifkan increment untuk detik jika tidak digunakan
        // minuteIncrement: 1,
        // Jika ingin detik, pastikan enableSeconds: true
        enableSeconds: true,

        // Catatan: Nilai default diambil dari attribute 'value' di HTML
    });

</script>
