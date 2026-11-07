<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SurveyDefect extends Model
{
    protected $guarded = [];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(SurveyDefectFile::class, 'id_deffect', 'id');
    }

    public function logRectifications(): HasMany
    {
        return $this->hasMany(LogRectification::class, 'id_deffect', 'id')->orderByDesc('datetime');
    }

    public function logRectificationFiles(): HasManyThrough
    {
        return $this->hasManyThrough(
            LogRectificationFile::class,
            LogRectification::class,
            'id_deffect',
            'id_log_deffect',
            'id',
            'id'
        )->orderByDesc('datetime');
    }
}
