<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::latest('id')->get();
        return view('backend.menus.index',compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validate = $request->validate([
            'name' => 'required|string|unique:menus',
            'desc' => 'nullable'
        ]);

        Menu::create([
            'name' => Str::slug($validate['name']),
            'description' => $validate['desc'],
            'deleteable' =>true,
        ]);

        flash()->success('Menus Created Successfully');
        return redirect()->back();

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }



    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu,$id)
    {
        $menu= Menu::find($id);

        // return $menu;
        return view('backend.menus.edit',compact('menu','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try {
            $validate = $request->validate([
            'name' => 'required|string|unique:menus,name,' . $id ,
            'desc' => 'nullable'
        ]);

        $menu = Menu::find($id);
        $menu -> name           =Str::slug($validate['name']);
        $menu -> description    = $validate['desc'];

        $menu->save();

        flash()->success('Menus updated successfully');
        return redirect()->route('menus.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $menu = Menu::find($id);
        if($menu->deleteable == true){
            $menu->delete();
            flash()->success('User deleted successfully');

        }else{
            flash()->error('Sory you can\'t delete system menu');
        }
        return redirect()->back();
    }
}
