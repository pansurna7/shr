<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{
    public function index(){
        $users=User::all();
        return view('backend.users.index',compact('users'));
    }

    public function create(){
        $user = User::where('id',0)->first();
        $roles = Role::get();
        return view('backend.users.create',compact('user','roles'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validate = $request->validate([
                        'userName' => 'required|string',
                        'email' => 'required|email|unique:users,email',
                        'nik' => 'required|unique:users,nik',
                        'password' =>'required|string|min:8',
                        'role' =>'required',
                        'role' => 'exists:roles,id',
                        'avatar' => 'required|image|mimes:jpg,png',
                    ]);
            if($request->hasFile('avatar')){
                $validate["avatar"] = $request->file("avatar")->store("avatar","public");
            }
            $user=User::create([
                    'name'      => $validate['userName'],
                    'email'     => $validate['email'],
                    'nik'       => $request->nik,
                    'password'  => Hash::make($request->password),
                    'status'    => filled('status'),
                    // 'avatar'    => $validate['avatar'],
                ]);
            if($request->role){
                $roles=Role::where('id', $validate['role'])->get();
                if(!$roles){
                    return null;
                }
                $user->assignRole($roles);
            }
            flash()->success('User created succsessfully');
            return redirect()->back();

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles= Role::all();
        return view('backend.users.edit',compact('user','roles','id'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            $validate=$request->validate([
                        'userName' => 'required',
                        'email' => 'required|email',
                        'nik' => 'required',
                        'password' =>'nullable|string|min:8',
                        'role' =>'required',
                        'role' => 'exists:roles,id',
                        'avatar'=> 'nullable'
                    ]);

            $user = User::find($id);
            $user->name = $validate['userName'];
            $user->email = $validate['email'];
            $user->nik = $validate['nik'];
            $user->status = filled($request->status);


            if(!empty($request->password)){
                $user->password = Hash::make($request->password);
            }else{
                unset($request->password);
            }
            if($request->hasFile('avatar')){
                if($user->avatar){
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file("avatar")->store("avatar","public");
            }

            $user->save();

            $user->roles()->detach();
            $roles=Role::where('id', $validate['role'])->get();
                if(!$roles){
                    return null;
                }
            $user->assignRole($roles);
            flash()->success('User updated successfully');
            return redirect()->route('users.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    public function delete($id){
        $user= User::find($id);
        $path =$user->avatar;
        if($user){
            if(Storage::disk('public')->exists($path)){
                Storage::disk('public')->delete($user->avatar);
            }
            $user->delete();
            flash()->success('User deleted successfully');
            return redirect()->back();
        }
    }
}
