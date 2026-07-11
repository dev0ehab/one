<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Spatie\Translatable\HasTranslations;

class ActivityType extends BaseModel
{
    use ModelTrait, HasTranslations;

    public array $translatable = ['name'];
    protected $fillable = ['name'];
}
