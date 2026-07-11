<?php


namespace App\Models\Relationships;

use App\Models\Branch;
use App\Models\City;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait UserRelations
{

    /**
     * Get the user's city.
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }


    
    /**
     * Get all of the branches for the Branch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    /**
     * Get the branch that owns the UserRelations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }


}
