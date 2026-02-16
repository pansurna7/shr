<form id="form-edit-leave"  class="needs-validation" data-id="{{$leave->id}}" novalidate>
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <div class="row mb-3">
        <label for="groupName" class="col-sm-3 col-form-label">Name Leave</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="text" class="form-control" id="name" name="name" value="{{ $leave->name }}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="quota" class="col-sm-3 col-form-label">Quota</label>
        <div class="col-sm-9">
            <div class="col-md-9">
                <input type="number" class="form-control" id="quota" name="quota" value="{{ $leave->quota }}" required>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <label for="is_active" class="col-sm-3 col-form-label">Status</label>
        <div class="col-sm-9">
            <div class="form-check form-switch mt-2">
                <input type="hidden" name="is_active" value="0">

                <input class="form-check-input" type="checkbox"
                    id="is_active" name="is_active" value="1"
                    {{ $leave->is_active == 1 ? 'checked' : '' }}>

                <label class="form-check-label text-white" for="is_active" id="status-label">
                    {{ $leave->is_active == 1 ? 'Active' : 'Not Active' }}
                </label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="text-center mt-5">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i class="lni lni-arrow-left-circle"></i>Close</button>
            <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Update</button>
        </div>
    </div>
</form>
