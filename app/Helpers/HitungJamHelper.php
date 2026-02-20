<?php
function hitungJamTerlambat($jadwal_jam_masuk,$jam_presensi)
{
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffTerlambat = $j2 - $j1;

    $jamTerlambat = floor($diffTerlambat / (60*60));
    $menitTerlambat = floor(($diffTerlambat - ($jamTerlambat *(60*60)))/60);

    $jTerlambat = $jamTerlambat <= 9 ? "0" . $jamTerlambat : $jamTerlambat;
    $mTerlambat = $menitTerlambat <= 9 ? "0" . $menitTerlambat : $menitTerlambat;

    $terlambat = $jTerlambat . ":" . $mTerlambat;

    return $terlambat;
}
