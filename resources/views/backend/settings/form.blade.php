

<div class="row mb-3">
    <label for="name" class="col-sm-3 col-form-label">System Name</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter System Name" value="{{ old('name', $setting->name ?? '') }}">
        @error('name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

</div>
<div class="row mb-3">
    <label for="slug" class="col-sm-3 col-form-label">slug</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="slug" name="slug"  value="{{ old('slug', $setting->slug ?? '') }}" readonly>
        @error('slug')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="mb-3">
    <div class="row">
        <div class="col-4">
            <label class="form-label">Logo</label>
                <input type="file" name="logo" id="logo" class="dropify form-control mb-4" data-allowed-file-extensions="jpg png jpeg" data-height="160" data-allowed-formats="portrait square" data-default-file="{{ asset('storage/' . $setting?->logo ?? "")}}" />
        </div>
    </div>
    @error('logo')
        <br>
        <span class="text-danger">{{ $message }}</span>
    @enderror

</div>

