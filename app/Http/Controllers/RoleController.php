<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::with('permissions')->get();
        return view('backend.roles.index', compact('roles'));
    }

    public function create()
    {
        $permission= Permission::all();
        $groupedPermissions = $permission->groupBy('group_name');
        $groupedPermissions = $groupedPermissions->sortKeys();
        return view('backend.roles.create',compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        try {
            $validate=$request->validate([
                        'roleName' => 'required|max:255|unique:roles,name',
                    ]);
            $role=Role::create(['name' =>$validate['roleName']]);

        $permissions = $request->input('permissions');
        if(!empty($permissions)){
            $role->syncPermissions($permissions);
        }
        flash()->success('Role created successfully');
        return redirect()->route('roles.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }


    }

    public function edit($id)
    {
        $groupedPermissions = Permission::all()->groupBy('group_name')->sortKeys();
        $role= Role::findById($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        // dd($rolePermissions);
        return view('backend.roles.edit',compact('groupedPermissions','role','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validate= $request->validate([
                            'roleName' => 'required|max:255',Rule::unique('role','name')->ignore($id),
                        ]);

            Role::where('id',$id)->update([
                'name' => $validate['roleName']
            ]);

            $role = Role::findById($id);
            $permissions = $request->input('permissions');

            if(!empty($permissions)){
                $role->syncPermissions($permissions);
            }
            flash()->success('Role updated successfully');
            return redirect()->route('roles.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    public function delete($id)
    {
        if($id){
            $role = Role::findById($id);
            if($role->name === "Super Admin"){
                flash()->error('This role cannot delete');
                return redirect()->back();
            }elseif(!is_null($role)){
                $role->delete();
                flash()->success('Role deleted successfully');
                return redirect()->back();
            }
        }
        // flash()->success('Role deleted successfully');
        // return redirect()->route('roles.index');

    }
}
