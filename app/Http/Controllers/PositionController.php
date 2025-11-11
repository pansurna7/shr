<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Departement;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $data = Position::with('departement')->get();
        $departements = Departement::pluck('name', 'id');
        return view('backend.position.index', compact('data','departements'));
    }

    public function store(Request $request)
    {
        $departement_id         =   $request->departement_id;
        $name                   =   $request->name;
        $positional_allowance   =   $request->tunjangan;
        $level                  =   $request->level;

        $data = [
            'departement_id'        => $departement_id,
            'positional_allowance'  => $positional_allowance,
            'name'                  => $name,
            'level'                 => $level,
        ];
        try {
            Position::create($data);
            flash()->success('Position created successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form');
            dd($e);
            // return back()->withErrors($e)->withInput();
        }
    }
    public function edit(Request $request)
    {
        $position = Position::find($request->id);
        $departements = Departement::pluck('name', 'id');
        return view('backend.position.edit',compact('position','departements'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $position = Position::findOrFail($id);
        $request->validate([
            'name' => 'required|max:255|unique:departements,name,' .$id,
            'level' => 'required|max:255'
        ]);
        $position->update($request->all());
        flash()->success('Position updated successfully');
        return response()->json([
            'message'       => 'Departement Updated Successfully!',
            'position'   => $position
        ]);

    }

    public function delete($id)
    {
        if($id){
            $position = Position::find($id);
            $position->delete();
            flash()->success('Position deleted successfully');
            return redirect()->back();
        }

    }
}
