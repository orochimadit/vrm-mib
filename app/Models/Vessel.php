<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vessel extends Model
{
    protected $guarded = [];

    public function vesselType(): BelongsTo
    {
        return $this->belongsTo(VesselType::class);
    }

    public function ownerCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'owner_company_id');
    }

    public function operatorCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'operator_company_id');
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class);
    }
}
