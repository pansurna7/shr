<div class="row mb-3">
    <label for="groupName" class="col-sm-3 col-form-label">Group Name</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="groupName" name="groupName" placeholder="Enter Group Name" value="{{ old('groupName', $permission->group_name ?? '') }}">
        @error('groupName')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

</div>
<div class="row mb-3">
    <label for="guardName" class="col-sm-3 col-form-label">Guard Name</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="guardName" name="guardName" placeholder="Enter Guard Name" value="{{ old('guardName', $permission->guard_name ?? 'web') }}" readonly>
        @error('guardName')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
<div class="row mb-3">
    <label for="permissionName" class="col-sm-3 col-form-label">Permission Name</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="permissionName" name="permissionName" placeholder="Enter Permision Name" value="{{ old('permissionName', $permission->name ?? '') }}">
        @error('permissionName')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

