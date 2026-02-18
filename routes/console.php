<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// Dijalankan setiap 1 Januari jam 00:00
Schedule::call(function () {
    $employees = DB::table('employees')->get();
    foreach ($employees as $emp) {
        DB::table('employees')->where('id', $emp->id)->update([
            'kuota_tahun_lalu' => $emp->kuota_tahun_ini, // Sisa tahun ini jadi tahun lalu
            'kuota_tahun_ini' => 12, // Reset jatah baru
        ]);
    }
})->yearlyOn(1, 1, '00:00');

// 2. Scheduler Nonaktifkan Akun Resign (Dijalankan SETIAP HARI jam 00:01)
// Scheduler dijalankan setiap hari jam 00:01
Schedule::call(function () {
    $today = now()->toDateString();

    // 1. Ambil data resign yang jatuh tempo hari ini atau yang sudah lewat tapi belum diproses
    $pendingResigns = DB::table('resignations')
        ->where('resign_date', '<=', $today)
        ->get();

    foreach ($pendingResigns as $resign) {
        // A. Update tabel Employees (Isi tanggal_keluar)
        DB::table('employees')
            ->where('id', $resign->employee_id)
            ->whereNull('tanggal_keluar') // Hanya update yang belum diset keluar
            ->update([
                'tanggal_keluar' => $resign->resign_date
            ]);

        // B. Update tabel Users (Set status 0)
        // Kita cari user_id melalui tabel employees
        $emp = DB::table('employees')->where('id', $resign->employee_id)->first();
        if ($emp && $emp->user_id) {
            DB::table('users')
                ->where('id', $emp->user_id)
                ->update(['status' => 0]);
        }
    }
})->dailyAt('00:01');
