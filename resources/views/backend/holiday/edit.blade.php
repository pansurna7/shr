<form id="form-edit-holiday"  class="needs-validation" data-id="{{$holiday->id}}" autocomplete="off"  novalidate>
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label fw-bold">Tanggal Libur</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                <input type="date" name="holiday_date" id="holiday_date" class="form-control @error('holiday_date') is-invalid @enderror" value="{{ $holiday->holiday_date }}" required>
            </div>
            @error('holiday_date')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-8">
            <label class="form-label fw-bold">Keterangan / Nama Libur</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Tahun Baru Imlek" value="{{ $holiday->description }}" required>
            @error('name')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="modal-footer">
        <div class="text-center mt-5">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="lni lni-arrow-left-circle"></i>Close</button>
            <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Update</button>
        </div>
    </div>
    </div>
</form>
