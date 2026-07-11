<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

class UserPermission extends BaseModel
{
    use ModelTrait, HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'type'
    ];


}
