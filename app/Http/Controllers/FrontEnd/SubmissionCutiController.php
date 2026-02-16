<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;
use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SubmissionCutiController extends Controller
{
    public function create()
    {
        $employee_id = Auth::user()->employee->id;
        $employee = DB::table('employees')->where('id', $employee_id)->first();

        // 1. Logika Syarat 1 Tahun Bergabung
        $tgl_masuk = $employee->tanggal_diangkat ? Carbon::parse($employee->tanggal_diangkat) : null;
        $boleh_cuti = $tgl_masuk ? $tgl_masuk->diffInYears(now()) >= 1 : false;

        // 2. Logika Akumulasi & Hangus Akhir Maret (Mulai April Hangus)
        $bulan_sekarang = date('n');
        // Jika bulan 1, 2, atau 3 (Jan-Mar), kuota tahun lalu masih aktif
        $kuota_lalu_aktif = ($bulan_sekarang <= 3) ? ($employee->kuota_tahun_lalu ?? 0) : 0;
        $total_sisa_cuti = $kuota_lalu_aktif + ($employee->kuota_tahun_ini ?? 0);

        $leaves = DB::table('leaves')->get();
        $holidays = DB::table('holidays')->pluck('holiday_date')->toArray();

        return view('frontend.presensi.submissions.cuti.create', compact(
            'employee', 'leaves', 'boleh_cuti', 'tgl_masuk',
            'total_sisa_cuti', 'kuota_lalu_aktif', 'holidays'
        ));
    }

    public function storecuti(Request $request)
    {
        // 1. Ambil data karyawan terbaru dari database
        $employee = DB::table('employees')->where('id', Auth::user()->employee->id)->first();
        if (!$employee) {
            return back()->with(['error' => 'Data karyawan tidak ditemukan.']);
        }

        // 2. Hitung Sisa Cuti Riil (Logika Akumulasi & Hangus April)
        $bulan_sekarang = date('n');
        // Jika bulan 1-3 (Jan-Mar), kuota tahun lalu masih berlaku
        $kuota_lalu_aktif = ($bulan_sekarang <= 3) ? ($employee->kuota_tahun_lalu ?? 0) : 0;
        $total_sisa_riil = $kuota_lalu_aktif + ($employee->kuota_tahun_ini ?? 0);

        // 3. Parsing Range Tanggal
        $tgl = explode(' to ', $request->tgl_izin);
        if (!isset($tgl[0])) {
            return back()->with(['error' => 'Tanggal belum dipilih.']);
        }

        $start = Carbon::parse($tgl[0]);
        $end = isset($tgl[1]) ? Carbon::parse($tgl[1]) : $start;

        // 4. Hitung Hari Kerja (Abaikan Sabtu, Minggu, & Libur Nasional)
        $holidays = DB::table('holidays')->pluck('holiday_date')->toArray();
        $period = CarbonPeriod::create($start, $end);
        $jml_hari = 0;

        foreach ($period as $date) {
            if (!$date->isWeekend() && !in_array($date->format('Y-m-d'), $holidays)) {
                $jml_hari++;
            }
        }

        if ($jml_hari == 0) {
            return back()->with(['error' => 'Tanggal yang dipilih adalah hari libur/akhir pekan.']);
        }

        // 5. VALIDASI: Bandingkan jml_hari dengan total_sisa_riil (Bukan dari Request)
        if ($jml_hari > $total_sisa_riil) {
            return back()->with([
                'error' => "Jatah cuti tidak mencukupi. Anda mengajukan $jml_hari hari, sisa kuota Anda adalah $total_sisa_riil hari."
            ]);
        }

        // 6. Simpan Data
        try {
            DB::table('submissions')->insert([
                'employee_id'   => $employee->id,
                'leave_id'      => $request->jenis_cuti,
                'condition'     => 4,
                'date'          => $start->format('Y-m-d'),
                'end_date'      => $end->format('Y-m-d'),
                'total_days'    => $jml_hari,
                'information'   => $request->ket,
            ]);

            return redirect()->route('presensi.izin')->with('success', 'Pengajuan cuti berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->with(['error' => 'Gagal menyimpan pengajuan: ' . $e->getMessage()]);
        }
    }


}
