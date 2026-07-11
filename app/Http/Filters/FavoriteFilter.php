<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class FavoriteFilter extends BaseFilters
{
    protected $filters = [
        'type',
    ];

    protected function type($value): Builder
    {
        return $this->builder;
    }
}

