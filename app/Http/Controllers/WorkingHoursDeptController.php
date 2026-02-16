<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Departement;
use App\Models\WorkingHoursDepartements;
use App\Models\WorkingHoursDeptDetail;
use App\Models\WorkingHours;
use Illuminate\Support\Facades\DB;
use App\Models\WorkingDay;

class WorkingHoursDeptController extends Controller
{
    public function index()
    {
        $whd = DB::table('working_hour_dept as whd')
        ->join('branches', 'whd.branch_code','=', 'branches.id')
        ->join('departements', 'whd.dept_code', '=', 'departements.id')
        ->select('whd.*','branches.name as branch_name', 'departements.name as dept_name')
        ->get();

        return view('backend.workinghoursdept.index',compact('whd'));
    }

    public function create()
    {
        $workinghours   = WorkingHours::all();
        $whd = WorkingHoursDepartements::where('id',0)->first();
        $branchs = Branch::all();
        $departements = Departement::all();
        return view('backend.workinghoursdept.create', compact('branchs','departements','whd','workinghours'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch'    => 'required',
            'dept'      => 'required'
        ]);
        $id_branch = $request->branch;
        $id_dept = $request->dept;
        $workinghour_id     = $request->idwk;
        $days               = $request->day;
        DB::beginTransaction();
        try {
            $whd = WorkingHoursDepartements::create([
                'branch_code'      => $id_branch,
                'dept_code'      => $id_dept,
            ]);



            $data=[];

            for ($i=0; $i < count($days) ; $i++) {
                $data[] = [
                    'whd_id'            =>$whd->id,
                    'days'              =>$days[$i],
                    'workinghour_id'    =>$workinghour_id[$i],
                ];
            }
            // dd($data);
            if(!empty($data)){
                WorkingHoursDeptDetail::insert($data);
            }
            DB::commit();
            flash()->success('Working Hours Departement created successfully');
            return redirect()->route('whd.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            flash()->error('Please fix the errors in the form :' . $e->getMessage());
            return back();
        }

    }

    public function edit($id)
    {
        $whd = WorkingHoursDepartements::findOrFail($id);
        $whdDetail = WorkingHoursDeptDetail::where('whd_id', $id)->get();
        $branchs        = Branch::all();
        $departements   = Departement::all();
        $workinghours   = WorkingHours::all();
        $workingdays = WorkingHoursDeptDetail::where('whd_id', $id) // Gunakan FK yang benar
                            ->pluck('workinghour_id', 'days') // Key: Nama Hari, Value: ID Jam Kerja
                            ->toArray();
        return view('backend.workinghoursdept.edit', compact('whd','whdDetail','branchs','departements','workinghours','workingdays'));
    }

    public function show($id)
    {
        $whd = WorkingHoursDepartements::findOrFail($id);
        $whdDetail = WorkingHoursDeptDetail::where('whd_id', $id)->get();
        $branchs        = Branch::all();
        $departements   = Departement::all();
        $workinghours   = WorkingHours::all();
        $workingdays = WorkingHoursDeptDetail::where('whd_id', $id) // Gunakan FK yang benar
                            ->pluck('workinghour_id', 'days') // Key: Nama Hari, Value: ID Jam Kerja
                            ->toArray();
        return view('backend.workinghoursdept.show', compact('whd','whdDetail','branchs','departements','workinghours','workingdays'));
    }

    public function update(Request $request,$id)
    {

        $workinghour_id     = $request->idwk;
        $days               = $request->day;
        DB::beginTransaction();
        try {
            WorkingHoursDeptDetail::where('whd_id', $id)->delete();
            $data=[];

            for ($i=0; $i < count($days) ; $i++) {
                $data[] = [
                    'whd_id'            =>$id,
                    'days'              =>$days[$i],
                    'workinghour_id'    =>$workinghour_id[$i],
                ];
            }
            // dd($data);
            if(!empty($data)){
                WorkingHoursDeptDetail::insert($data);
            }
            DB::commit();
            flash()->success('Working Hours Departement created successfully');
            return redirect()->route('whd.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            flash()->error('Please fix the errors in the form :' . $e->getMessage());
            return back();
        }

    }

    public function delete($id)
    {
        if($id){
            $whd = WorkingHoursDepartements::findOrFail($id);
            $whd->delete();
            flash()->success('Permission deleted successfully');
            return redirect()->back();
        }
    }
}
