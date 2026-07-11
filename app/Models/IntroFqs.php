<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class IntroFqs extends BaseModel
{
    use HasTranslations;

    protected $fillable = ['title', 'description', 'intro_fqs_category_id'];

    public $translatable = ['title', 'description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(IntroFqsCategory::class, 'intro_fqs_category_id', 'id');
    }
}

