<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProviderFilter extends BaseFilters
{
    protected $filters = [
        'name',
        'search',
        'city_id',
        'category_id',
        'activity_type_id',
        'is_active',
        'is_approved',
        'nearby',
        'highest_rate',
        'min_rate',
        'has_booking_service',
        "has_offers",
        'sort',
    ];

    /**
     * When the client sends lat/lng, always select a computed `distance` column so
     * API resources can expose `distance_km` even if `sort` is not `nearest`.
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        $this->selectDistanceWhenLatLngPresent();

        foreach ($this->getFilters() as $filter) {
            $value = $this->request->query($filter);
            if ($this->request->has($filter)) {
                $methodName = Str::camel($filter);
            } else {
                $methodName = 'default' . Str::studly($filter);
            }

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this->builder;
    }

    protected function selectDistanceWhenLatLngPresent(): void
    {
        if (! $this->request->filled(['lat', 'lng'])) {
            return;
        }

        $lat = (float) $this->request->input('lat');
        $lng = (float) $this->request->input('lng');

        $this->builder->selectRaw(
            '*, (6371 * acos(
                cos(radians(?))
                * cos(radians(CAST(lat AS DECIMAL(10,7))))
                * cos(radians(CAST(lng AS DECIMAL(10,7))) - radians(?))
                + sin(radians(?))
                * sin(radians(CAST(lat AS DECIMAL(10,7))))
            )) AS distance',
            [$lat, $lng, $lat]
        );
    }

    protected function name($value): Builder
    {
        if ($value) {
            return $this->builder->where('trade_name', 'like', '%' . $value . '%');
        }

        return $this->builder;
    }

    protected function search($value): Builder
    {
        if (!$value) {
            return $this->builder;
        }

        return $this->builder->where(function ($query) use ($value) {
            $query->where('trade_name', 'like', '%' . $value . '%')
                ->orWhere('map_desc', 'like', '%' . $value . '%');
        });
    }

    protected function cityId($value): Builder
    {
        if ($value) {
            return $this->builder->where('city_id', $value);
        }

        return $this->builder;
    }

    protected function categoryId($value): Builder
    {
        if ($value) {
            return $this->builder->where('category_id', $value);
        }

        return $this->builder;
    }

    protected function activityTypeId($value): Builder
    {
        return $this->categoryId($value);
    }

    protected function isActive($value): Builder
    {
        if ($value !== null) {
            return $this->builder->where('is_active', filter_var($value, FILTER_VALIDATE_BOOLEAN));
        }

        return $this->builder;
    }

    protected function isApproved($value): Builder
    {
        if ($value === null || $value === '') {
            return $this->builder;
        }

        if ($value === 'pending') {
            return $this->builder->whereNull('is_approved');
        }

        return $this->builder->where('is_approved', filter_var($value, FILTER_VALIDATE_BOOLEAN));
    }

    protected function minRate($value): Builder
    {
        if ($value !== null && $value !== '') {
            return $this->builder->where('avg_rate', '>=', (float) $value);
        }

        return $this->builder;
    }

    protected function hasBookingService($value): Builder
    {
        if ($value !== null && $value !== '') {
            $enabled = filter_var($value, FILTER_VALIDATE_BOOLEAN);

            if ($enabled) {
                return $this->builder
                    ->where('has_booking_service', true)
                    ->where(function ($query) {
                        $query->whereNull('booking_service_expires_at')
                            ->orWhere('booking_service_expires_at', '>', now());
                    });
            }

            return $this->builder->where(function ($query) {
                $query->where('has_booking_service', false)
                    ->orWhere('booking_service_expires_at', '<=', now());
            });
        }

        return $this->builder;
    }

    protected function hasOffers($value): Builder
    {
        if ($value !== null && $value !== '') {
            return $this->builder->where('show_in_offers', filter_var($value, FILTER_VALIDATE_BOOLEAN));
        }
        return $this->builder;
    }

    protected function nearby(): Builder
    {
        if (! request()->filled(['lat', 'lng'])) {
            return $this->builder;
        }

        $radius = (float) request('radius', 20);

        return $this->builder
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->having('distance', '<=', $radius)
            ->orderBy('distance', 'asc');
    }

    protected function highestRate($value): Builder
    {
        if ($value) {
            return $this->builder->orderByDesc('avg_rate');
        }

        return $this->builder;
    }

    protected function sort($value): Builder
    {
        return match ($value) {
            'nearest'       => $this->nearby(),
            'highest_rate'  => $this->builder->orderByDesc('avg_rate'),
            'lowest_rate'   => $this->builder->orderBy('avg_rate'),
            default         => $this->builder,
        };
    }
}
