<?php

namespace App\Models;

use App\Enums\CategoryTypeEnum;
use App\Models\Cascading\DeleteCategory;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends BaseModel
{
    use ModelTrait, HasTranslations;

    public const IMAGEPATH = 'categories';

    protected $fillable = [
        'name',
        'image',
        'parent_id',
        'type',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public $translatable = ['name'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public function getFolderName(): string
    {
        return 'provider-categories';
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            if ($category->parent_id === null) {
                (new DeleteCategory($category))->checkProviders();
            }
        });
    }
}
