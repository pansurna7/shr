<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded =['id'];

    public function departemen()
    {
        return $this->belongsTo(Departement::class);
    }

    public function position()
    {
        return $this->belongsTo(position::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
