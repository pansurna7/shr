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
            <label for="userName" class="col-sm-3 col-form-label">User</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="userName" name="userName" placeholder="Enter User Name" value="{{ old('userName', $user->name ?? '') }}">
                @error('userName')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{old('email',$user->email ?? '')}}">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        {{-- <div class="row mb-3">
            <label for="nik" class="col-sm-3 col-form-label">NIK</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="nik" name="nik" placeholder="Enter NIK" value="{{ old('nik', $user->nik ?? '') }}">
                @error('nik')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div> --}}
        <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">Password</label>
            <div class="col-sm-9">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="role" class="col-sm-3 col-form-label">Role</label>
            <div class="col-sm-9">
                <select class="form-select" id="role" name="role">
                    @if ($user)
                        @foreach ($roles as $role )
                            <option value="{{$role->id}}"{{$user->hasRole($role->id) ? 'selected' : ''}}>{{$role->name}}</option>
                        @endforeach
                    @else
                    <option value="">{{ $user->role->name ?? 'Choose Role'}}</option>
                        @if (count($roles) > 0)
                            @foreach ($roles as $role )
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        @endif
                    @endif

                </select>
                @error('role')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-3">
        <div class="mt-0">
            <div class="row mb-1">
                    <input type="file" name="avatar" id="avatar" class="dropify form-control mb-4" data-allowed-file-extensions="jpg png jpeg" data-height="160" data-default-file="{{ asset('storage/' . $user?->avatar ?? "")}}"  />
            </div>
            @error('avatar')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-check-mb-3 form-switch fs-5">
            <input type="checkbox" class="form-check-input" id="checkStatus"  name="status"  {{$user?->status  ? 'checked' :''}} >
            <label for="checkStatus">status</label>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.dropify').dropify();
        });
    </script>

@endpush
