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
