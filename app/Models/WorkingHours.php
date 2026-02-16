<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkingHours extends Model
{
    protected $guarded = ['id'];

    public function employees(): HasMany
{
    // Menampilkan semua karyawan yang menggunakan jadwal ini
    return $this->hasMany(Employee::class);
}

public function workingDays(): BelongsToMany
{
    // Menampilkan hari-hari yang termasuk dalam jadwal ini, melalui tabel pivot
    return $this->belongsToMany(WorkingDay::class, 'working_hour_working_day');
}
}

