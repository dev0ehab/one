<?php

namespace App\Services\Entity;

use App\Http\Filters\FavoriteFilter;
use App\Models\Favorite;
use App\Models\Provider;

class FavoriteService extends BaseService
{
    public function __construct(Favorite $favorite, FavoriteFilter $filter)
    {
        $this->model = $favorite;
        $this->filter = $filter;
    }

    public function indexApi()
    {
        $data = $this->model::filter($this->filter)
            ->where('client_id', auth()->id())
            ->where('favoritable_type', Provider::class)
            ->with([
                'favoritable' => function ($q) {
                    $q->with(['category', 'city', 'workDays'])->withIsFavorite()->withHasActiveBooking();
                },
            ])
            ->latest()
            ->when(request()->has('is_paginated') && !request()->is_paginated, function ($query) {
                return $query->get();
            })
            ->when(!request()->has('is_paginated') || request()->is_paginated, function ($query) {
                return $query->paginate(request()->paginate ?? 10);
            });

        return $this->apiCollectionResponse($data);
    }

    public function toggle($item)
    {
        $clientId = auth()->id();

        $favorite = $this->model::where('client_id', $clientId)
            ->where('favoritable_type', $item->getMorphClass())
            ->where('favoritable_id', $item->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
            $message = __('apis.removed_from_favorites');
        } else {
            $this->model::create([
                'client_id'        => $clientId,
                'favoritable_type' => $item->getMorphClass(),
                'favoritable_id'   => $item->id,
            ]);
            $isFavorite = true;
            $message = __('apis.added_to_favorites');
        }

        return [
            'status' => 'success',
            'message' => $message,
            'is_favorite' => $isFavorite,
        ];
    }
}

