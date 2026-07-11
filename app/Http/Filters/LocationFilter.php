<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class LocationFilter extends BaseFilters
{
    protected $filters = [
        'mine',
    ];

    protected function mine($value): Builder
    {
        if ($value) {
            return $this->builder->where('client_id', auth()->user()->id);
        }
        
        return $this->builder;
    }
}

