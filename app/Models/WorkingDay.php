<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkingDay extends Model
{
    protected $guarded = ['id'];

    public function workinghours():BelongsToMany
    {
        return $this->belongsToMany(WorkingHours::class);
    }
}
