<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Position extends Model
{
    protected $guarded =['id'];

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }


}
