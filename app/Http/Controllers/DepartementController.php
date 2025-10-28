<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    public function index()
    {
        $deps = Departement::orderBy('id','desc')->get();
        return view('backend.departement.index',compact('deps'));
    }

    public function store(Request $request)
    {
        $kode = $request->kode;
        $name = $request->name;

        $data = [
            'code'  => $kode,
            'name'  => $name,
        ];
        try {
            Departement::create($data);
            flash()->success('Depertement created successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form');
            dd($e);
            // return back()->withErrors($e)->withInput();
        }
    }

    public function edit(Request $request)
    {
        $departement = Departement::findOrFail($request->id);
        return view('backend.departement.edit',compact('departement'));
    }

    public function update(Request $request, $id)
    {
        $departement = Departement::findOrFail($id);
        // dd($departement);
        $request->validate([
            'code' => 'required|unique:departements,code,' .$id,
            'name' => 'required|max:255'
        ]);
        $departement->update($request->all());
        flash()->success('Depertement updated successfully');
        return response()->json([
            'message'       => 'Departement Updated Successfully!',
            'departement'   => $departement
        ]);

    }

    public function delete($id)
    {
        if($id){
            $permission = Departement::find($id);
            $permission->delete();
            flash()->success('Permission deleted successfully');
            return redirect()->back();
        }

    }
}
