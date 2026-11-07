<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VesselType extends Model
{
    protected $guarded = [];

    public function vessels(): HasMany
    {
        return $this->hasMany(Vessel::class);
    }
}
