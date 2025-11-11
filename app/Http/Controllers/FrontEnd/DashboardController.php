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

        $id = Auth::user()->employee->id;

        $presensi_today= DB::table('presences')->where('employee_id',$id)->where('date',$today)->first();
        //  dd($presensi_today);
        $history_on_mount = DB::table('presences')
            ->where('employee_id',$id)
            ->whereRaw("MONTH(date)='$month'")
            ->whereRaw("YEAR(date)='$year'")
            ->orderBy('date')
            ->get();

        $nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni","July","Agustus","September","Oktober","November","Desember"];

        $rekap_presensi= DB::table('presences')
            ->selectRaw("COUNT(employee_id) as jml_hadir,SUM(IIF(time_in > '07:00:00', 1, 0)) AS jml_telat")
            ->where('employee_id',$id)
            ->whereRaw("MONTH(date)='$month'")
            ->whereRaw("YEAR(date)='$year'")
            ->first();

        $leader_board = DB::table('presences')
                        // JOIN ke tabel employees
                        ->join('employees', 'presences.employee_id', '=', 'employees.id')

                        // ✨ JOIN ke tabel positions (menggunakan foreign key di employees) ✨
                        ->join('positions', 'employees.position_id', '=', 'positions.id')

                        // Filter tanggal
                        ->where('date', $today)

                        // Pilih kolom yang diperlukan, termasuk nama posisi
                        ->select(
                            'presences.*', // Ambil semua kolom dari presences
                            'employees.first_name', // Contoh kolom dari employees
                            'employees.last_name',
                            'avatar',

                            // ✨ Ambil nama posisi dari tabel positions dan beri alias
                            'positions.name AS position_name'
                        )
                        ->get();

        // dd($leader_board);
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
            ->where('employee_id', $id)
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
