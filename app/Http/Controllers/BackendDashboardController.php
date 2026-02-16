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
        // 1. Hitung yang Izin, Sakit, Cuti (Query sebelumnya)
        $rekap_izin = DB::table('submissions')
            ->selectRaw("
                SUM(CASE WHEN condition = 1 THEN 1 ELSE 0 END) as jml_izin,
                SUM(CASE WHEN condition = 2 THEN 1 ELSE 0 END) as jml_sakit,
                SUM(CASE WHEN condition = 3 THEN 1 ELSE 0 END) as jml_sakit_dokter,
                SUM(CASE WHEN condition = 4 THEN 1 ELSE 0 END) as jml_cuti
            ")
            ->where('status', 1)
            ->where('date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->first();

        // 2. Hitung yang Hadir (Presensi) hari ini
        $jml_hadir = DB::table('presences')
            ->where('date', $today)
            ->count();

        // 3. Hitung Total Seluruh Karyawan
        $total_karyawan = DB::table('employees')->count();

        // 4. Hitung yang Tidak Hadir (Tanpa Keterangan)
        $jml_absen = ($rekap_izin->jml_izin ?? 0) +
                    ($rekap_izin->jml_sakit ?? 0) +
                    ($rekap_izin->jml_sakit_dokter ?? 0) +
                    ($rekap_izin->jml_cuti ?? 0);

        $tidak_hadir = $total_karyawan - ($jml_hadir + $jml_absen);
        // Pastikan tidak negatif
        $tidak_hadir = $tidak_hadir < 0 ? 0 : $tidak_hadir;
        $daftar_izin_hari_ini = DB::table('submissions')
        ->join('employees', 'submissions.employee_id', '=', 'employees.id')
        ->select('employees.first_name', 'submissions.condition', 'submissions.date', 'submissions.end_date','submissions.leave_id')
        ->where('submissions.status', 1) // Hanya yang disetujui
        ->where('submissions.date', '<=', $today)
        ->where('submissions.end_date', '>=', $today)
        ->get();
        // dd($rekap_izin);

        // chart
        $chartData = [
            'labels' => [],
            'hadir'  => [],
            'absen'  => [], // Izin, Sakit, Cuti
            'alpha'  => []  // Tidak hadir tanpa keterangan
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartData['labels'][] = date('d M', strtotime($date));

            // 1. Hitung yang Hadir
            $jml_hadir = DB::table('presences')->where('date', $date)->count();
            $chartData['hadir'][] = $jml_hadir;

            // 2. Hitung yang Absen (Izin/Sakit/Cuti disetujui)
            $jml_absen = DB::table('submissions')
                ->where('status', 1)
                ->where('date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->count();
            $chartData['absen'][] = $jml_absen;

            // 3. Hitung Alpha (Total Karyawan - (Hadir + Absen))
            $jml_alpha = $total_karyawan - ($jml_hadir + $jml_absen);
            $chartData['alpha'][] = $jml_alpha < 0 ? 0 : $jml_alpha; // Pastikan tidak negatif
        }
        return view('backend.dashboard',compact(
                                            'employee',
                                            'history_on_mount',
                                            'nama_bulan',
                                            'month',
                                            'year',
                                            'rekap_presensi',
                                            'rekap_izin',
                                            'jml_hadir',
                                            'total_karyawan',
                                            'jml_absen',
                                            'tidak_hadir',
                                            'daftar_izin_hari_ini',
                                            'chartData'
                                            )
                                        );
    }
}
