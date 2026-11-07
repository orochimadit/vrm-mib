<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogRectificationFile extends Model
{
    protected $table = 'log_rectification_file';
    protected $primaryKey = 'id_log_file';

    protected $fillable = [
        'id_log_deffect',
        'file_type',
        'file_name',
        'file_title',
        'file_link',
        'file_gdrive_id',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function logRectification(): BelongsTo
    {
        return $this->belongsTo(LogRectification::class, 'id_log_deffect', 'id');
    }

    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_type === 'link') {
            return $this->file_link;
        }

        if (!empty($this->file_gdrive_id)) {
            return "https://drive.google.com/file/d/{$this->file_gdrive_id}/view";
        }

        if (!empty($this->file_name)) {
            return asset('storage/attachment/' . $this->file_name);
        }

        return null;
    }
}
