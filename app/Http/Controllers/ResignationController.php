<?php

namespace App\Http\Controllers;

use App\Models\Resignations;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ResignationController extends Controller
{
    public function index()
    {
        $resignations = Resignations::with('employee')->latest()->get();
        $activeEmployees = Employee::whereNull('tanggal_keluar')->get(); // Hanya karyawan aktif yang bisa resign
        return view('backend.resign.index', compact('resignations', 'activeEmployees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'resign_date' => 'required|date',
            'reason' => 'required',
            'document' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($request->employee_id);
            $today = date('Y-m-d');

            // 1. Upload Dokumen
            $documentPath = null;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = 'resign_' . $employee->id . '_' . date('Ymd_His') . '_' . $file->getClientOriginalName();
                $documentPath = $file->storeAs('resignation_docs', $fileName, 'public');
            }

            // 2. Simpan Riwayat Resign
            Resignations::create([
                'employee_id' => $request->employee_id,
                'resign_date' => $request->resign_date,
                'reason' => $request->reason,
                'description' => $request->description,
                'document' => $documentPath,
            ]);

            // 3. LOGIKA UPDATE LANGSUNG: Jika resign hari ini atau sudah lewat
            if ($request->resign_date <= $today) {
                // Update tabel Employee
                $employee->update([
                    'tanggal_keluar' => $request->resign_date,
                ]);

                // Update tabel User
                if ($employee->user_id) {
                    User::where('id', $employee->user_id)->update([
                        'status' => 0,
                    ]);
                }
                $message = 'Karyawan berhasil diproses resign dan akun dinonaktifkan hari ini.';
            } else {
                $message = 'Jadwal resign berhasil dicatat (akan aktif otomatis pada tanggal ' . date('d/m/Y', strtotime($request->resign_date)) . ').';
            }

            DB::commit();
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $resignation = Resignations::findOrFail($id);
        $employee = Employee::findOrFail($resignation->employee_id);

        DB::beginTransaction();
        try {
            // 1. Kembalikan karyawan jadi aktif (kosongkan tanggal_keluar)
            $employee->update([
                'tanggal_keluar' => null,
            ]);

            // 2. Aktifkan kembali akun User agar bisa login lagi
            if ($employee->user_id) {
                User::where('id', $employee->user_id)->update([
                    'status' => 1, // Set kembali ke Aktif
                ]);
            }

            // 3. Hapus file dokumen dari storage jika ada
            if ($resignation->document) {
                Storage::disk('public')->delete($resignation->document);
            }

            // 4. Hapus record riwayat resign
            $resignation->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data resign dibatalkan. Karyawan dan akun login telah diaktifkan kembali.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal membatalkan resign: ' . $e->getMessage());
        }
    }
}
