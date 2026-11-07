<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Survey extends Model
{
    protected $guarded = [];

    protected $casts = [
        'inspection_data' => 'array',
        'date_of_survey' => 'date',
        'due_date' => 'date',
        'policy_period_start' => 'date',
        'policy_period_end' => 'date',
        'risk_score' => 'decimal:2',
    ];

    public function vessel(): BelongsTo
    {
        return $this->belongsTo(Vessel::class);
    }

    public function surveyor(): BelongsTo
    {
        return $this->belongsTo(Surveyor::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function insuranceCompany(): BelongsTo
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function defects(): HasMany
    {
        return $this->hasMany(SurveyDefect::class);
    }
}
