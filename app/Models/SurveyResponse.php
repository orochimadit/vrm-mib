<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyResponse extends Model
{
    protected $table = 'survey_responses';
    protected $primaryKey = 'response_id';

    protected $fillable = [
        'survey_id',
        'item_id',
        'response',
        'risk_impact',
        'risk_rating',
        'remarks_from_surveyor',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class, 'survey_id', 'id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(SurveyItem::class, 'item_id', 'item_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(SurveyResponseAttachment::class, 'response_id', 'response_id');
    }
}
