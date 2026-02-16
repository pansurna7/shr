<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    protected $guarded =['id'];

    public function user():HasOne
    {
        return $this->hasOne(User::class);
    }

    public function submissions()
    {
        // Employee memiliki BANYAK Submission
        return $this->hasMany(Submission::class);
    }

    public function departemen()
    {
        return $this->belongsTo(Departement::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function presences():HasMany
    {
        return $this->hasMany(Presence::class);
    }

    public function workingHour(): BelongsTo
    {
        // FK: working_hour_id
        return $this->belongsTo(WorkingHours::class);
    }

    public function workingDays(): HasMany
    {
        // Karyawan (satu) memiliki banyak (many) entri hari kerja/jadwal.
        return $this->hasMany(WorkingDay::class, 'employee_id');
    }

    // Relasi Many-to-Many ke Locations
    public function assigned_locations()
    {
        return $this->belongsToMany(Location::class, 'employee_location');
    }

}
