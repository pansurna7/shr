<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkingHours;

use function Flasher\Prime\flash;

class WorkingHoursController extends Controller
{
    public function index()
    {
        $working_hours = WorkingHours::orderBy('id')->get();
        return view('backend.workinghours.index',compact('working_hours'));
    }

    public function store(Request $request)
    {
        $data = [
                    'name'          => $request->name,
                    'start_time'    => $request->awaljm,
                    'entry_time'    => $request->entry_time,
                    'end_time'      => $request->end_time,
                    'out_time'      => $request->out_time,
                ];
        try {
            WorkingHours::create($data);
            flash()->success('Working Hours successfully created');
            return redirect()->back();
        } catch (\Exception $e) {
            flash()->error('Please fix the errors in the form' .$e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $workinghour = WorkingHours::find($request->id);
        return view('backend.workinghours.edit', compact('workinghour'));
    }

    public function update(Request $request, $id)
    {
        $workinghour = WorkingHours::find($id);
        $request->validate([
            'name' => 'required|max:255'
        ]);
        // dd($request->all());
        $workinghour->update([
            'name'          => $request->name,
            'start_time'    => $request->start_time,
            'entry_time'    => $request->entry_time,
            'end_time'      => $request->end_time,
            'out_time'      => $request->out_time,
        ]);
        flash()->success('Working Hours updated successfully');
        return response()->json([
            'message'       => 'Branch Updated Successfully!',
            'WorkingHours'   => $workinghour
        ]);

    }

    public function delete($id)
    {
        if($id){
            $workinghour = WorkingHours::find($id);
            $workinghour->delete();
            flash()->success('Working Hour deleted successfully');
            return redirect()->back();
        }

    }
}
