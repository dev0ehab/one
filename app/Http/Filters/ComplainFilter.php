<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ComplainFilter extends BaseFilters
{
    protected $filters = [
        'mine',
        'status',
    ];

    protected function mine($value): Builder
    {
        if ($value) {
            return $this->builder
                ->where('complainable_id', auth()->id())
                ->where('complainable_type', get_class(auth()->user()));
        }

        return $this->builder;
    }

    protected function status($value): Builder
    {
        if ($value) {
            return $this->builder->where('status', $value);
        }

        return $this->builder;
    }
}
