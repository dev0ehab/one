<?php

namespace App\Http\Filters;

use App\Enums\InquiryRecipientStatusEnum;
use App\Enums\InquiryStatusEnum;
use App\Models\Client;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Builder;

class InquiryFilter extends BaseFilters
{
    protected $filters = [
        'mine',
        'provider',
        'status',
        'tab',
    ];

    protected function mine($value): Builder
    {
        if ($value && auth('sanctum')->check() && auth()->user() instanceof Client) {
            return $this->builder->where('client_id', auth()->id());
        }

        return $this->builder;
    }

    protected function provider($value): Builder
    {
        if (!$value || !auth('sanctum')->check() || !auth()->user() instanceof Provider) {
            return $this->builder;
        }

        return $this->builder->whereHas('recipients', function ($q) {
            $q->where('provider_id', auth()->id());
        });
    }

    protected function status($value): Builder
    {
        if ($value) {
            return $this->builder->where('status', $value);
        }

        return $this->builder;
    }

    protected function tab($value): Builder
    {
        if (!auth('sanctum')->check()) {
            return $this->builder;
        }

        if (auth()->user() instanceof Client) {
            return match ($value) {
                'accepted'  => $this->builder->where('status', InquiryStatusEnum::ACCEPTED),
                'rejected'  => $this->builder->where('status', InquiryStatusEnum::REJECTED),
                'cancelled' => $this->builder->where('status', InquiryStatusEnum::CANCELLED),
                default     => $this->builder->where('status', InquiryStatusEnum::PENDING),
            };
        }

        if (auth()->user() instanceof Provider) {
            $providerId = auth()->id();

            return match ($value) {
                'accepted' => $this->builder->whereHas('recipients', function ($q) use ($providerId) {
                    $q->where('provider_id', $providerId)
                        ->where('status', InquiryRecipientStatusEnum::ACCEPTED);
                }),
                'rejected' => $this->builder->whereHas('recipients', function ($q) use ($providerId) {
                    $q->where('provider_id', $providerId)
                        ->where('status', InquiryRecipientStatusEnum::REJECTED);
                }),
                default    => $this->builder
                    ->where('status', InquiryStatusEnum::PENDING)
                    ->whereHas('recipients', function ($q) use ($providerId) {
                        $q->where('provider_id', $providerId)
                            ->where('status', InquiryRecipientStatusEnum::PENDING);
                    }),
            };
        }

        return $this->builder;
    }
}
