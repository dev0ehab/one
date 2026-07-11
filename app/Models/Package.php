<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Package extends BaseModel
{
    use ModelTrait, HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'price',
        'ads_count',
        'duration_days',
        'is_active',
        'sort',
    ];

    public array $translatable = ['name', 'description'];

    protected $casts = [
        'name'        => 'array',
        'description' => 'array',
        'price'       => 'float',
        'is_active'   => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ProviderSubscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
