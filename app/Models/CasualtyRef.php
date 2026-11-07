<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CasualtyRef extends Model
{
    protected $guarded = [];

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}
