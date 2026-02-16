<form id="form-edit-branch"  class="needs-validation" data-id="{{$branch->id}}" novalidate>
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Kode Branch</label>
        <div class="col-sm-9">
            <div class="col-md-4">
                <input type="text" class="form-control" id="code" name="code" value="{{$branch->code}}" readonly>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Nama Branch</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="name" name="name" value="{{$branch->name}}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="address" name="address" value="{{$branch->address}}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Koordinat</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="location" name="location" value="{{$branch->location}}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Radius</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="radius" name="radius" value="{{$branch->radius}}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Meal Allowance</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="mela_allowance" name="meal_allowance" value="{{$branch->meal_allowance}}">
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
