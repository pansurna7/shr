<form id="form-edit-position"  class="needs-validation" data-id="{{$position->id}}" novalidate>
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Departement</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <select id="edit_departement_id" name="departement_id" class="form-select" required>
                <option value="">-- Pilih Departemen --</option>
                    @foreach($departements as $id => $name)
                        <option value="{{ $id }}" {{ $id == $position->departement_id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Nama Jabatan</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="name" name="name" value="{{$position->name}}">
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Level Jabatan </label>
        <div class="col-sm-9">
            <div class="col-md-4">
                <input type="text" class="form-control" id="level" name="level" value="{{$position->level}}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Tunjangan Jabatan </label>
        <div class="col-sm-9">
            <div class="col-md-4">
                <input type="text" class="form-control" id="tunjangan" name="tunjangan" value="{{$position->positional_allowance}}">
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
