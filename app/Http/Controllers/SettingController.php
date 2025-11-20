<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting= Settings::where('id','1')->first();
        // dd($setting);
        return view('backend.settings.setting',compact('setting'));
    }
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $validate = $request->validate([
            'name' => 'required',
            'slug'=> 'required',
            'logo' => 'image|mimes:png,jpg'
            ]);
            if($request->hasFile('logo')){
                if($request->oldLogo)
                {
                    Storage::disk('public')->delete($request->oldLogo);
                }
            }
            Settings::where('id',1)->update($validate);
            flash()->success('Sytem updated successfully');
            return redirect()->route('settings.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }
}
