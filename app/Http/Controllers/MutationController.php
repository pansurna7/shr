<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mutations; // Pastikan nama model sesuai (Mutation atau Mutations)
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Support\Facades\DB;

class MutationController extends Controller
{
    public function index()
    {
        $mutations = Mutations::with(['employee', 'oldBranch', 'newBranch', 'oldPosition', 'newPosition'])
            ->orderBy('mutation_date', 'desc')
            ->get();

        $employees = Employee::all();
        $branches = Branch::all();
        $positions = Position::all();
        $locations = Location::where('is_active', 1)->get();

        return view('backend.mutations.index', compact('mutations', 'employees', 'branches', 'positions', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id'     => 'required',
            'mutation_date'   => 'required|date',
            'new_branch_id'   => 'required',
            'new_position_id' => 'required',
            'location_ids'    => 'required|array',
        ]);

        $employee = Employee::findOrFail($request->employee_id);

        DB::beginTransaction();
        try {
            // 1. Simpan Riwayat Mutasi
            Mutations::create([
                'employee_id'     => $employee->id,
                'mutation_date'   => $request->mutation_date,
                'old_branch_id'   => $employee->branch_id,
                'old_position_id' => $employee->position_id,
                'new_branch_id'   => $request->new_branch_id,
                'new_position_id' => $request->new_position_id,
                'description'     => $request->description,
            ]);

            // 2. Update Master Karyawan
            $employee->update([
                'branch_id'   => $request->new_branch_id,
                'position_id' => $request->new_position_id,
            ]);

            // 3. Sinkronisasi Lokasi Absen
            $employee->assigned_locations()->sync($request->location_ids);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['message' => 'Mutasi berhasil disimpan!']);
            }
            return redirect()->back()->with('success', 'Mutasi berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $mutation = Mutations::findOrFail($id);

        // Mengambil ID lokasi yang saat ini aktif untuk karyawan tersebut
        $currentLocations = DB::table('employee_location')
            ->where('employee_id', $mutation->employee_id)
            ->pluck('location_id')
            ->toArray();

        return response()->json([
            'mutation'          => $mutation,
            'current_locations' => $currentLocations
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mutation_date'   => 'required|date',
            'new_branch_id'   => 'required',
            'new_position_id' => 'required',
            'location_ids'    => 'required|array',
        ]);

        $mutation = Mutations::findOrFail($id);
        $employee = Employee::findOrFail($mutation->employee_id);

        DB::beginTransaction();
        try {
            // 1. Update Riwayat Mutasi
            $mutation->update([
                'mutation_date'   => $request->mutation_date,
                'new_branch_id'   => $request->new_branch_id,
                'new_position_id' => $request->new_position_id,
                'description'     => $request->description,
            ]);

            // 2. Update Master Karyawan (Sesuai update mutasi terbaru)
            $employee->update([
                'branch_id'   => $request->new_branch_id,
                'position_id' => $request->new_position_id,
            ]);

            // 3. Update Lokasi Absen
            $employee->assigned_locations()->sync($request->location_ids);

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['message' => 'Data mutasi berhasil diperbarui!']);
            }
            return redirect()->back()->with('success', 'Data mutasi berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Gagal memperbarui: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mutation = Mutations::findOrFail($id);
            $mutation->delete();

            return redirect()->back()->with('success', 'Riwayat mutasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
