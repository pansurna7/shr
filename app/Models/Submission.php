<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    protected $guarded=['id'];



    public function employee()
    {
        // Submission dimiliki oleh SATU Employee
        // Eloquent secara default akan mencari kolom 'employee_id'
        return $this->belongsTo(Employee::class);
    }
}


