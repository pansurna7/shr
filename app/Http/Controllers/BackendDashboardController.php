<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;

class BackendDashboardController extends Controller
{
    public function index()
    {
        $today= date("Y-m-d");
        $month= date("m")*1;
        $year = date("Y");

        $employee = Employee::count();

        $history_on_mount = DB::table('presences')
            ->whereRaw("MONTH(date)='$month'")
            ->whereRaw("YEAR(date)='$year'")
            ->orderBy('date')
            ->get();

        //dd($history_on_mount);

        $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];

        $rekap_presensi= DB::table('presences')
            ->selectRaw("COUNT(employee_id) as jml_hadir, SUM(IIF(time_in > '07:00:00', 1, 0)) AS jml_telat")
            ->whereRaw("date = ?", [$today])
            ->first();


        // untuk mysql server
        // $rekap_izin = DB::table('submissions')
        //     ->selectRaw("
        //         SUM(IF(condition = 0, 1, 0)) as jml_izin,
        //         SUM(IF(condition IN (1, 2), 1, 0)) as jml_sakit
        //     ")
        //     ->where('nik', $nik)
        //     // 💡 Use WHERE instead of whereRaw for MONTH and YEAR to keep it cleaner
        //     ->whereRaw("MONTH(date) = ?", [$month])
        //     ->whereRaw("YEAR(date) = ?", [$year])
        //     // 💡 Simplification: pass the column, operator, and value separately
        //     ->where('status', 1)
        //     ->first();

        // Query SQLSERVER
        $rekap_izin = DB::table('submissions')
            ->selectRaw("
                SUM(CASE WHEN [condition] = 0 THEN 1 ELSE 0 END) as jml_izin,
                SUM(CASE WHEN [condition] IN (1, 2) THEN 1 ELSE 0 END) as jml_sakit
            ")
            ->whereRaw("([date]) = ?", [$today])
            ->where('status', 1)
            ->first();
        // dd($rekap_izin);
        return view('backend.dashboard',compact(
                                            'employee',
                                            'history_on_mount',
                                            'nama_bulan',
                                            'month',
                                            'year',
                                            'rekap_presensi',
                                            'rekap_izin'
                                            )
                                        );
    }
}
