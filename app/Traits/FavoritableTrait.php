<?php

namespace App\Traits;

use App\Models\Favorite;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait FavoritableTrait
{
    public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function scopeWithIsFavorite($query)
    {
        $clientId = auth()->id();

        if (!$clientId) {
            return $query;
        }

        return $query->withExists([
            'favorites as is_favorite' => fn ($q) => $q->where('client_id', $clientId),
        ]);
    }
}
