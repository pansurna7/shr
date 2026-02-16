<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionIzinController extends Controller
{
    public function create()
    {
        return view('frontend.presensi.submissions.izin.create');
    }

    public function cektglpengajuan(Request $request)
    {
        $employee_id = Auth::user()->employee->id;

        try {
            // 1. Inisialisasi variabel tanggal
            $start_new = null;
            $end_new = null;

            // 2. Cek apakah input berupa Range atau Single Date
            if (str_contains($request->tgl_izin, ' to ')) {
                // Jika RANGE
                $dates = explode(' to ', $request->tgl_izin);
                $start_new = \Carbon\Carbon::parse(trim($dates[0]))->format('Y-m-d');
                $end_new   = \Carbon\Carbon::parse(trim($dates[1]))->format('Y-m-d');
            } else {
                // Jika SINGLE DATE (1 Hari)
                $start_new = \Carbon\Carbon::parse(trim($request->tgl_izin))->format('Y-m-d');
                $end_new   = $start_new; // Akhir tanggal sama dengan awal
            }

            // 3. Cek Overlap di Database
            // Logika ini otomatis mencakup pengecekan 1 hari jika $start == $end
            $cek = \App\Models\Submission::where('employee_id', $employee_id)
                ->where(function ($query) use ($start_new, $end_new) {
                    $query->where('date', '<=', $end_new)
                            ->where('end_date', '>=', $start_new);
                })
                ->count();

            return response()->json($cek);

        } catch (\Exception $e) {
            Log::error("Error Cek Tgl: " . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeizin(Request $request)
    {
        $employee_id = Auth::user()->employee->id;
        $durasi = $request->jml_hari;
        // 1. Inisialisasi Tanggal (Menangani Range atau Single)
        try {
            if (str_contains($request->tgl_izin, ' to ')) {
                $dates = explode(' to ', $request->tgl_izin);
                $tgl_mulai   = \Carbon\Carbon::parse(trim($dates[0]))->toDateString();
                $tgl_selesai = \Carbon\Carbon::parse(trim($dates[1]))->toDateString();
            } else {
                $tgl_mulai   = \Carbon\Carbon::parse(trim($request->tgl_izin))->toDateString();
                $tgl_selesai = $tgl_mulai; // Jika 1 hari, tgl selesai sama dengan tgl mulai
            }
        } catch (\Exception $e) {
            flash()->error('Format tanggal tidak valid.');
            return redirect()->back()->withInput();
        }

        // 2. Siapkan Data
        $data = [
            'employee_id'   => $employee_id,
            'date'          => $tgl_mulai,   // Kolom tgl mulai
            'end_date'      => $tgl_selesai, // Kolom tgl selesai (Pastikan kolom ini ada di DB)
            'total_days'    => $durasi,
            'condition'     => 1,
            'information'   => $request->ket,
        ];



        try {
            // 4. Simpan ke Database
            Submission::create($data);

            flash()->success('Pengajuan izin berhasil dibuat.');
            return redirect()->route('presensi.izin');

        } catch (\Exception $e) {
            flash()->error('Gagal membuat pengajuan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
