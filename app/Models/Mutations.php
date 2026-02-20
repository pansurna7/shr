<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutations extends Model
{
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    // Relasi ke Branch Lama
    public function oldBranch()
    {
        return $this->belongsTo(Branch::class, 'old_branch_id');
    }


    // Relasi ke Branch Baru
    public function newBranch()
    {
        return $this->belongsTo(Branch::class, 'new_branch_id');
    }

    // Relasi ke Posisi/Jabatan Lama
    public function oldPosition()
    {
        return $this->belongsTo(Position::class, 'old_position_id');
    }

    // Relasi ke Posisi/Jabatan Baru
    public function newPosition()
    {
        return $this->belongsTo(Position::class, 'new_position_id');
    }
}
