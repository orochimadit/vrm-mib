<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveyItem extends Model
{
    protected $table = 'survey_items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'insurance_product_id',
        'ref_no',
        'category',
        'item_name',
        'risk_value_expected',
        'risk_value',
    ];

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'item_id', 'item_id');
    }
}
