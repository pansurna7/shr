
@push('css')
    <style>
        .dropify-wrapper .dropify-message p{
            font-size: initial;
        }

        .form-switch .form-check-input:focus {
            border-color: rgba(0, 0, 0, 0.25);
            outline: 0;
            box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(0,0,0,0.25)'/></svg>");
        }
        .form-switch .form-check-input:checked {
            background-color: #30D158;
            border-color: #30D158;
            border: none;
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(255,255,255,1.0)'/></svg>");
        }
    </style>

@endpush
<div class="row">
    <div class="col-9">
        <div class="row mb-3">
            <label for="name" class="col-sm-3 col-form-label">Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ old('name',$menu->name ?? "") }}" required autofocus>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="desc" class="col-sm-3 col-form-label">Description</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="desc" name="desc"  value="{{ $menu->description ?? old('desc')}}">
                @error('desc')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready(function () {

        });
    </script>

@endpush
