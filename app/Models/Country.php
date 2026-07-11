<?php

namespace App\Models;

use App\Models\Cascading\DeleteCountry;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Country extends BaseModel
{
    use ModelTrait, HasTranslations;    

    public const IMAGEPATH = 'countries';

    protected $fillable = ['name', 'code', 'image'];

    public array $translatable = ['name'];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($country) {
            (new DeleteCountry($country))
                ->checkCities();
        });
    }

}
