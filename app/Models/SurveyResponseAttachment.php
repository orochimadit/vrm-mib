<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponseAttachment extends Model
{
    protected $table = 'survey_responses_attachment';
    protected $primaryKey = 'id_attachment';

    protected $fillable = [
        'response_id',
        'title',
        'type',
        'file',
        'link',
        'gdrive_id',
    ];

    public function response(): BelongsTo
    {
        return $this->belongsTo(SurveyResponse::class, 'response_id', 'response_id');
    }

    public function getHasPhysicalFileAttribute(): bool
    {
        if (empty($this->file)) {
            return false;
        }

        return file_exists(storage_path('app/public/attachments/' . $this->file));
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->type === 'link') {
            return $this->link;
        }

        if (!empty($this->file)) {
            return route('document.attachment', ['filename' => $this->file]);
        }

        if (!empty($this->gdrive_id)) {
            return "https://drive.google.com/file/d/{$this->gdrive_id}/view";
        }

        return null;
    }
}
