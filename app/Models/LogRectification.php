<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogRectification extends Model
{
    protected $table = 'log_rectification';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_deffect',
        'value',
        'category',
        'datetime',
        'id_admin',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function defect(): BelongsTo
    {
        return $this->belongsTo(SurveyDefect::class, 'id_deffect', 'id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_admin', 'id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(LogRectificationFile::class, 'id_log_deffect', 'id');
    }
}
