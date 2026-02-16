<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubmissionTimeController extends Controller
{
    public function  create()
    {
        return view('frontend.presensi.submissions.jam.create');
    }



    public function cektglpengajuan(Request $request)
{
    $user_id = Auth::user()->employee->id;
    $tgl_input = $request->tgl_izin;

    if (!$tgl_input) {
        return response()->json(['status' => 'success']);
    }

    // 1. CEK ABSENSI DI TABEL PRESENCES
    // Kita cek apakah data absen sudah ada dan apakah masih ada yang kosong
    $absen = DB::table('presences')
            ->where('employee_id', $user_id)
            ->whereDate('date', $tgl_input)
            ->first();

    // Jika absen ditemukan DAN jam_in sudah ada DAN jam_out sudah ada
    if ($absen && !empty($absen->time_in) && !empty($absen->time_out)) {
        return response()->json([
            'status' => 'error',
            'message' => 'Data absensi tanggal ' . date('d-m-Y', strtotime($tgl_input)) . ' sudah lengkap (Masuk & Pulang). Tidak perlu koreksi.'
        ]);
    }

    // 2. CEK DUPLIKASI PENGAJUAN (Izin/Cuti/Sakit)
    // Di sini kita HANYA memblokir jika sudah ada pengajuan yang BUKAN Koreksi (1-4)
    // Atau jika Anda ingin mengizinkan koreksi berkali-kali, kita tambahkan where 'condition' != 5
    $cekSubmission = Submission::where('employee_id', $user_id)
        ->where('status', '!=', 2) // Bukan yang ditolak
        ->where('condition', '!=', 5) // <--- KUNCI: Abaikan jika sudah ada pengajuan koreksi sebelumnya
        ->where(function ($query) use ($tgl_input) {
            $query->where(function ($q) use ($tgl_input) {
                $q->whereNotNull('end_date')
                  ->whereDate('date', '<=', $tgl_input)
                  ->whereDate('end_date', '>=', $tgl_input);
            })
            ->orWhere(function ($q) use ($tgl_input) {
                $q->whereNull('end_date')
                  ->whereDate('date', '=', $tgl_input);
            });
        })
        ->first();

    if ($cekSubmission) {
        $mulai = date('d-m-Y', strtotime($cekSubmission->date));
        $selesai = $cekSubmission->end_date ? date('d-m-Y', strtotime($cekSubmission->end_date)) : $mulai;

        // Jenis pengajuan (Optional: untuk memperjelas pesan)
        $labels = [1 => 'Izin', 2 => 'Sakit', 3 => 'Sakit Dokter', 4 => 'Cuti'];
        $jenis = $labels[$cekSubmission->condition] ?? 'Pengajuan';

        return response()->json([
            'status' => 'error',
            'message' => "Gagal! Tanggal ".date('d-m-Y', strtotime($tgl_input))." sudah terdaftar dalam pengajuan $jenis: $mulai s/d $selesai."
        ]);
    }

    // Jika sampai di sini, artinya:
    // - Absen belum lengkap (salah satu kosong)
    // - Tidak ada bentrokan dengan Cuti/Izin
    return response()->json(['status' => 'success']);
}


    public function storeKoreksi(Request $request)
    {
        $request->validate([
            'tgl_koreksi' => 'required|date',
            'keterangan' => 'required'
        ]);

        DB::table('submissions')->insert([
            'employee_id' => Auth::user()->employee->id,
            'date' => $request->tgl_koreksi,
            'jam_in_pengajuan' => $request->jam_in,
            'jam_out_pengajuan' => $request->jam_out,
            'information' => $request->keterangan,
            'condition' => 5, // Kode khusus koreksi absen
            'status' => 0,    // Waiting
            'created_at' => now()
        ]);

        return redirect()->route('presensi.izin')->with('success', 'Pengajuan jam Presensi berhasil dikirim.');
    }
}
