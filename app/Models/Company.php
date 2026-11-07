<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $guarded = [];

    public function ownedVessels(): HasMany
    {
        return $this->hasMany(Vessel::class, 'owner_company_id');
    }

    public function operatedVessels(): HasMany
    {
        return $this->hasMany(Vessel::class, 'operator_company_id');
    }
}
