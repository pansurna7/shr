@extends('backend.layouts.app')
@section('title','Edit')
@push('css')
    <style>
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
@section('content')

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Role Edit</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}"><i class="bx bx-user-circle"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>

            </nav>

        </div>
    </div>

    <!--end breadcrumb-->
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="card border-top border-0 border-4 border-white">
                <div class="card-body">
                    <div class="border p-4 rounded">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-cog me-1 font-22 text-white"></i>
                            </div>
                            <h5 class="mb-0 text-white">Edit Role</h5>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            <div class="row mb-3">
                                <label for="roleName" class="col-sm-3 col-form-label">Role Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="roleName" name="roleName" placeholder="Enter Role Name" value="{{ $role->name }}">
                                    @error('roleName')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <hr>
                                <span for="name" class="text-center fs-4">Permissions Management</span>
                                <div class="form-check-mb-3 form-switch text-center fs-5">
                                    <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1">
                                    <label for="checkPermissionAll">All</label>
                                </div>
                                <hr>

                                @foreach ($groupedPermissions as $groupName=>$permissions )
                                    <div class="col">
                                        <div class="form-check form-switch fs-5 fw-bold">
                                            <input type="checkbox" class="form-check-input" id="{{ Str::slug($groupName) }}Management"  value="{{ $groupName }}" onclick="cekPermissionByGroup('role-{{ $loop->iteration }}-management-checkbox', this)"
                                            {{ $permissions->every(function($permission) use ($rolePermissions){
                                                return in_array($permission->name, $rolePermissions);
                                            }) ? 'checked' :'' }}>
                                            <label for="checkPermission" class="form-check-label">{{ $groupName }}</label>
                                        </div>

                                        <div class="mt-4 mb-3 fs-6 role-{{ $loop->iteration }}-management-checkbox">
                                            @foreach ($permissions->sortBy('name') as  $permission)
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="checkPermission{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                                    {{ in_array($permission->name, $rolePermissions) ? 'checked':'' }}>
                                                    <label for="checkPermission{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            {{-- <div class="form-group">
                                <label for="name">Permissions</label>
                                <hr>
                                <div class="form-check-mb-3 form-switch">
                                    <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="{{ count($groupedPermissions->flatten()) === count($role->permissions) ? 'checked' : ''}}">
                                    <label for="checkPermissionAll">All</label>
                                </div>
                                <hr>
                                @foreach ($groupedPermissions as $groupName=>$permissions )
                                    <div class="row mb-3">
                                        <div class="col-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="{{ Str::slug($groupName) }}Management"  value="{{ $groupName }}" onclick="cekPermissionByGroup('role-{{ $loop->iteration }}-management-checkbox', this)"
                                                {{ $permissions->every(function($permission) use ($rolePermissions){
                                                    return in_array($permission->name, $rolePermissions);
                                                }) ? 'checked' :'' }}>
                                                <label for="checkPermission" class="form-check-label">{{ $groupName }}</label>
                                            </div>
                                        </div>
                                        <div class="col-9 role-{{ $loop->iteration }}-management-checkbox">
                                            @foreach ($permissions->sortBy('name') as  $permission)
                                                <div class="form-check form-switch">
                                                    <input type="checkbox" class="form-check-input" id="checkPermission{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}"
                                                    {{ in_array($permission->name, $rolePermissions) ? 'checked':'' }}>
                                                    <label for="checkPermission{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div> --}}
                            <div class="row">
                                <label class="col-sm-3 col-form-label"></label>
                                <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-light px-5"><i class="bx bx-save"></i>Save</button>
                                    <a type="btn btn-light" href="{{ route('roles.index') }}" class="btn btn-light px-5"><i class="lni lni-arrow-left-circle"></i>Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        function cekPermissionByGroup(className, checkThis)
        {
            const groupIdName= $("#" + checkThis.id);
            const classCheckBox = $('.' + className + ' input');

            if(groupIdName.is(':checked')){
                classCheckBox.prop('checked', true)
            }else{
                classCheckBox.prop('checked', false)
            }
            implemenAllChecked();
        }
        function implemenAllChecked(){
           const countPermissions = $('input[name="permissions[]"]').length;
           const countCheckedPermissions = $('input[name="permissions[]"]:checked').length;

           if(countPermissions === countCheckedPermissions){
                $('#checkPermissionAll').prop('checked', true);
           }else{
                $('#checkPermissionAll').prop('checked', false);
           }
        }


        $(document).ready(function () {
            implemenAllChecked();
            $("#checkPermissionAll").click(function () {
                const isChecked = $(this).is(":checked");
                $('input[type="checkbox"]').prop('checked', isChecked);
            });

            $('input[name="permissions[]"]').on('change', function(){
                // alert('ok');
                implemenAllChecked();

                const groupContainer = $(this).closest('.col');
                const groupCheckbox = groupContainer.find('.form-check-input').first();
                const permissionCheckboxes = groupContainer.find('input[name="permissions[]"]');

                const hasChecked = permissionCheckboxes.filter(':checked').length > 0;
                groupCheckbox.prop('checked',hasChecked);

            })


        });
    </script>
@endpush
