<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id'];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_location');
    }
}
