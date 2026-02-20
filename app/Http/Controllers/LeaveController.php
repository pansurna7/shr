<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::all();
        return view('backend.leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'quota' => $request->quota,
            'is_active' => $request->is_active,
        ];

        try {
            Leave::create($data);
            flash()->success('Leave created successfully');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form' .$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $leave =  Leave::find($request->id);
        return view('backend.leaves.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $leave = Leave::findOrFail($id);
        $data = [
            'name' => $request->name,
            'quota' => $request->quota,
            'is_active' => $request->is_active,
        ];
        $leave->update($data);
        flash()->success('Leave updated successfully');
        return response()->json([
            'message'       => 'Leave Updated Successfully!',
            'Leave'   => $leave
        ]);

    }

    public function delete($id)
    {
        if($id){
            $leave = Leave::find($id);
            $leave->delete();
            flash()->success('Leave deleted successfully');
            return redirect()->back();
        }

    }
}
