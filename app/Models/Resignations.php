<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Guard;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resignations extends Model
{
    protected $guarded = ['id'];
    public function employee(): BelongsTo
    {
        // Pastikan nama modelnya adalah 'Employee'
        // dan foreign key di tabel resignations adalah 'employee_id'
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
