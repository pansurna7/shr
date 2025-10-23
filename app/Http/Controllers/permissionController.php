<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class permissionController extends Controller
{
    public function index(){
        $permissions = Permission::all();
        // foreach($permissions as $groupName => $groupPermissions){
        //     echo $groupName;
        //     foreach($groupPermissions as $permission){
        //         echo $permission->name;
        //     }
        // }
        return view('backend.permissions.index',compact('permissions'));
    }

    public function create(){
        return view('backend.permissions.create');
    }

    public function store(Request $request){

        try{
            $validate=$request->validate([
            'groupName' => 'required',
            'permissionName' => 'required||unique:permissions,name',
        ]);
            Permission::create([
                'group_name'    => $validate['groupName'],
                'guard_name'    => 'web',
                'name'          => $validate['permissionName'],
            ]);
            flash()->success('Permission created successfully');
            return  redirect()->back();

        }catch (\Illuminate\Validation\ValidationException $e) {
            // Return back with validation errors
            flash()->error('Please fix the errors in the form');

            return back()->withErrors($e->validator)->withInput();
        }
    }
    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('backend.permissions.edit',compact('permission'));
    }

    public function update(Request $request,$id)
    {
        try {
           $validate=$request->validate([
                    'groupName' => 'required',
                    'permissionName' => 'required',
            ]);

            Permission::where('id',$id)->update([
                'group_name' => $validate['groupName'],
                'name' => $validate['permissionName']
            ]);
            flash()->success('Permission updated successfully');
            return redirect()->route('permissions.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }
    }

    public function delete($id)
    {
        if($id){
            $permission = Permission::findById($id);
            $permission->delete();
            flash()->success('Permission deleted successfully');
            return redirect()->back();
        }

    }
}
