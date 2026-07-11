<?php

namespace App\Http\Filters;

use App\Enums\BookingStatusEnum;
use Illuminate\Database\Eloquent\Builder;

class BookingFilter extends BaseFilters
{
    protected $filters = [
        'tab',
        'status',
        'provider_id',
        'mine',
        'provider',
    ];

    protected function tab($value): Builder
    {
        return match ($value) {
            'new'             => $this->builder->where('status', BookingStatusEnum::NEW),
            'pending_payment' => $this->builder->where('status', BookingStatusEnum::PENDING_PAYMENT),
            'current'         => $this->builder->where('status', BookingStatusEnum::IN_PROGRESS),
            'finished'        => $this->builder->whereIn('status', [BookingStatusEnum::COMPLETED, BookingStatusEnum::CANCELLED]),
            default           => $this->builder,
        };
    }

    protected function status($value): Builder
    {
        if ($value) {
            return $this->builder->where('status', $value);
        }

        return $this->builder;
    }

    protected function providerId($value): Builder
    {
        if ($value) {
            return $this->builder->where('provider_id', $value);
        }

        return $this->builder;
    }

    protected function mine($value): Builder
    {
        if (request("mine")) {
            return $this->builder->where('client_id', auth()->id());
        }

        return $this->builder;
    }

    protected function provider($value): Builder
    {
        if ($value && auth('sanctum')->check() && auth()->user() instanceof \App\Models\Provider) {
            return $this->builder->where('provider_id', auth()->id());
        }

        return $this->builder;
    }
}
