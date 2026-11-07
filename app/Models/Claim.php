<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $guarded = [];

    protected $casts = [
        'case_handled' => 'date',
        'date_of_loss' => 'date',
        'period_of_policy_start' => 'date',
        'period_of_policy_end' => 'date',
        'settled_date' => 'date',
        'estimated_claim' => 'decimal:2',
        'amount' => 'decimal:2',
        'tsi_lol' => 'decimal:2',
        'settled_claim_amount' => 'decimal:2',
    ];

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function insuranceProduct(): BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    public function casualtyRef(): BelongsTo
    {
        return $this->belongsTo(CasualtyRef::class);
    }
}
