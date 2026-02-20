<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Announcement;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Set default bulan dan tahun jika request kosong
        $employee = Employee::where('id', Auth::user()->employee->id)->first();
        $branch = Branch::where('id', $employee->branch_id)->first();
        $month = $request->bulan ?? date('m');
        $year = $request->tahun ?? date('Y');

        $user = Auth::user();
        $id = $user->employee->id;
        $today = date('Y-m-d');

        // 1. Data Absensi Hari Ini
        $presensi_today = DB::table('presences')->where('employee_id', $id)->where('date', $today)->first();

        // 2. Ambil Histori Bulanan User
        $history_on_mount = DB::table('presences')
            ->join('employees', 'presences.employee_id', '=', 'employees.id')
            // Ambil data posisi untuk mendapatkan dept_id (sesuaikan jika dept_id ada di tabel lain)
            ->join('positions', 'employees.position_id', '=', 'positions.id')
            // Join ke master jadwal departemen
            ->leftJoin('working_hour_dept as whd', function ($join) {
                $join->on('employees.branch_id', '=', 'whd.branch_code')->on('positions.departement_id', '=', 'whd.dept_code');
            })
            // Join ke detail jadwal berdasarkan hari (Senin, Selasa, dsb)
            ->leftJoin('working_hour_dept_detail as dwhd', function ($join) {
                $join
                    ->on('whd.id', '=', 'dwhd.whd_id')
                    // Kita pakai helper untuk mendapatkan nama hari dari kolom date
                    ->on(DB::raw("FORMAT(presences.date, 'dddd', 'id-ID')"), '=', 'dwhd.days');
            })
            // Join ke master jam kerja untuk ambil kolom 'name'
            ->leftJoin('working_hours', 'dwhd.workinghour_id', '=', 'working_hours.id')
            ->select(
                'presences.*',
                'working_hours.name', // Kita beri alias agar aman
                'working_hours.start_time', // Jam mulai boleh scan
                'working_hours.entry_time', // Jam terakhir masuk (batas terlambat)
                'working_hours.out_time'
            )
            ->where('presences.employee_id', $id)
            ->whereMonth('presences.date', $month)
            ->whereYear('presences.date', $year)
            ->orderBy('presences.date', 'desc')
            ->get();
            // dd($history_on_mount);
        // 3. Ambil Leaderboard (Hadir Hari Ini)
        // dd($history_on_mount);
        $leader_board = DB::table('employees')
            ->select('employees.first_name', 'employees.avatar', 'positions.name as position_name', 'presences.time_in')
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->leftJoin('presences', function ($join) use ($today) {
                $join->on('employees.id', '=', 'presences.employee_id')->where('presences.date', '=', $today);
            })
            ->orderBy('presences.time_in', 'asc')
            ->get();

        // 4. Rekap Izin, Sakit, Cuti
        $rekap_izin = DB::table('submissions')
            ->selectRaw(
                "
                SUM(CASE WHEN condition = 1 THEN total_days ELSE 0 END) as jml_izin,
                SUM(CASE WHEN condition IN (2, 3) THEN total_days ELSE 0 END) as jml_sakit,
                SUM(CASE WHEN condition IN (4, 5) THEN total_days ELSE 0 END) as jml_cuti
            ",
            )
            ->where('employee_id', $id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 1)
            ->first();

        // 5. Rekap Kehadiran (Total hari hadir)
        $rekap_presensi = DB::table('presences')->where('employee_id', $id)->whereMonth('date', $month)->whereYear('date', $year)->count();

        $nama_bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        // 1. Tentukan rentang waktu (1 bulan terakhir dari hari ini)
        $oneMonthAgo = Carbon::now()->subMonth();
        $today = Carbon::today()->toDateString();

        // 2. Query Announcement
        $announcements = Announcement::where('is_active', 1)
            // Filter: Hanya yang dibuat dalam 30 hari terakhir
            ->where('created_at', '>=', $oneMonthAgo)
            // Filter Opsional: Hanya yang masih dalam rentang tgl tayang (jika memakai start/end date)
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('frontend.index', compact('presensi_today', 'history_on_mount', 'leader_board', 'rekap_izin', 'rekap_presensi', 'nama_bulan', 'month', 'year', 'employee','announcements'));
    }

    public function downloadFile($filename)
{
    // Tentukan path lengkap file di storage
    $path = storage_path('app/public/announcements/' . $filename);

    // Cek apakah file benar-benar ada
    if (!file_exists($path)) {
        return abort(404, 'File tidak ditemukan.');
    }

    // Jalankan perintah download
    return response()->download($path);
}
}
