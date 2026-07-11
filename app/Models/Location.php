<?php

namespace App\Models;

use App\Http\Filters\Filterable;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Location extends BaseModel
{
    use Filterable, ModelTrait;

    protected $table = 'locations';

    protected $fillable = [
        'client_id',
        'name',
        'lat',
        'lng',
        'map_desc',
        'country_id',
        'city_id',
        'is_main_location',
    ];

    protected $casts = [
        'lat'              => 'float',
        'lng'              => 'float',
        'is_main_location' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
