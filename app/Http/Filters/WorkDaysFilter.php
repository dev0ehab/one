<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class WorkDaysFilter extends BaseFilters
{
    protected $filters = [
        'mine',
    ];

    protected function mine($value): Builder
    {
        if ($value) {
            return $this->builder->where('userable_id', auth()->user()->id)
                ->where('userable_type', get_class(auth()->user()));
        }
    }

}

