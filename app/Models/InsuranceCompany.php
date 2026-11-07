<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceCompany extends Model
{
    protected $guarded = [];

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }
}
