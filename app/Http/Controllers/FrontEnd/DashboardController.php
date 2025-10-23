<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today= date("Y-m-d");
        $month= date("m")*1;
        $year = date("Y");

        $nik = Auth::user()->nik;

        $presensi_today= DB::table('presences')->where('nik',$nik)->where('date',$today)->first();

        $history_on_mount = DB::table('presences')
            ->where('nik',$nik)
            ->whereRaw("MONTH(date)='$month'")
            ->whereRaw("YEAR(date)='$year'")
            ->orderBy('date')
            ->get();

            $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];

        $rekap_presensi= DB::table('presences')
            ->selectRaw("COUNT(nik) as jml_hadir,SUM(IIF(time_in > '07:00:00', 1, 0)) AS jml_telat")
            ->where('nik',$nik)
            ->whereRaw("MONTH(date)='$month'")
            ->whereRaw("YEAR(date)='$year'")
            ->first();

        $leader_board=DB::table('presences')
            -> join("users", "presences.nik","=","users.nik")
            ->where('date',$today)
            ->get();

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
            ->where('nik', $nik)
            ->whereRaw("MONTH([date]) = ?", [$month])
            ->whereRaw("YEAR([date]) = ?", [$year])
            ->where('status', 1)
            ->first();
        // dd($rekap_izin);
        return view('frontend.index',compact(
                                            'presensi_today',
                                            'history_on_mount',
                                            'nama_bulan',
                                            'month',
                                            'year',
                                            'rekap_presensi',
                                            'leader_board',
                                            'rekap_izin'
                                            )
                                        );
    }
}
