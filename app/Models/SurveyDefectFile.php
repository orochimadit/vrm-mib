<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyDefectFile extends Model
{
    protected $table = 'survey_responses_deffect_file';
    protected $primaryKey = 'id_file';

    protected $fillable = [
        'id_deffect',
        'type',
        'file',
        'title',
        'link',
        'gdrive_id',
    ];

    public function defect(): BelongsTo
    {
        return $this->belongsTo(SurveyDefect::class, 'id_deffect', 'id');
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->type === 'link') {
            return $this->link;
        }

        if (!empty($this->gdrive_id)) {
            return "https://drive.google.com/file/d/{$this->gdrive_id}/view";
        }

        if (!empty($this->file)) {
            return asset('storage/attachment/' . $this->file);
        }

        return null;
    }
}
