<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
// use Spatie\BackupServer\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        try {
            $validate=$request->validate([
                        'full_name'     => 'required',
                        'email'         => 'required|email',
                        'password'      =>'nullable|string|min:8',
                        'mobile'        => 'nullable|string|max:15',
                        'address'        => 'nullable|string|max:255',
                        'avatar'        => 'nullable'
                    ]);

            // return $validate;

            $user = User::find($request->id);
            $user->full_name = $request->full_name;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
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
            flash()->success('Profile updated successfully');
            return redirect()->back();
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
