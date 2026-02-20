<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('id', 'ASC')->get();
        return view('backend.branch.index', compact('branches'));
    }
    public function store(Request $request)
    {


        $data = [
            'code'              => $request->kode,
            'name'              => $request->name,
            'address'           => $request->address,
            // 'location'          => $request->location,
            // 'radius'            => $request->radius,
            'meal_allowance'    => $request->meal_allowance,
        ];
        try {
            Branch::create($data);
            flash()->success('Branch created successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form' .$e->getMessage());

            // return back()->withErrors($e)->withInput();
        }
    }
    public function edit(Request $request)
    {
        $branch = Branch::findOrFail($request->id);
        return view('backend.branch.edit',compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::find($id);
        $request->validate([
            'code' => 'required|unique:branches,code,' .$id,
            'name' => 'required|max:255'
        ]);
        $branch->update($request->all());
        flash()->success('Branch updated successfully');
        return response()->json([
            'message'       => 'Branch Updated Successfully!',
            'Branch'   => $branch
        ]);

    }
}
