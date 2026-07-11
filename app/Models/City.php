<?php

namespace App\Models;

use App\Models\Cascading\DeleteCity;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

class City extends BaseModel
{
    use ModelTrait, HasTranslations;

    public const IMAGEPATH = 'cities';

    protected $fillable = ['name', 'country_id', 'image'];

    public array $translatable = ['name'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function clients(): HasManyThrough
    {
        return $this->hasManyThrough(Client::class, Location::class, 'city_id', 'id', 'id', 'client_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function providers(): HasMany
    {
        return $this->hasMany(Provider::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($city) {
            (new DeleteCity($city))
                ->checkClients()
                ->checkProviders();
        });
    }
}
