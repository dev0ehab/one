<?php

namespace App\Services\Entity;

use App\Enums\BookingStatusEnum;
use App\Enums\InquiryStatusEnum;
use App\Http\Resources\Api\Client\Ad\AdResource;
use App\Http\Resources\Api\Client\Booking\BookingResource;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\ProviderAd;

class ClientHomeService
{
    public function dashboard(): array
    {
        $banners = ProviderAd::active()
            ->with(['images', 'provider:id,trade_name,image,avg_rate'])
            ->latest('approved_at')
            ->limit((int) request('banner_limit', 10))
            ->get();

        return [
            'banners'          => AdResource::collection($banners),
            'bookings_count'   => $this->bookingsCount(),
            'inquiries_count'  => $this->inquiriesCount(),
            'offers_count'     => ProviderAd::active()->count(),
        ];
    }

    public function schedule(): array
    {
        /** @var Client $client */
        $client = auth()->user();

        $search = trim((string) request('search', ''));

        $bookings = Booking::query()
            ->where('client_id', $client->id)
            ->whereDate('scheduled_at', today())
            ->where('status', '!=', BookingStatusEnum::CANCELLED)
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('provider', fn ($q) => $q->where('trade_name', 'like', '%' . $search . '%'));
            })
            ->with(['provider', 'images', 'rate'])
            ->orderBy('scheduled_at')
            ->get();

        return [
            'title'    => __('apis.client_home_schedule_title'),
            'date'     => today()->toDateString(),
            'bookings' => $bookings->map(function ($booking) {
                return [
                    'service_title'       => $booking->service_title,
                    'service_description' => $booking->service_description,
                    'provider_name'       => $booking->provider->trade_name,
                ];
            }), 
        ];
    }

    protected function bookingsCount(): int
    {
        $client = auth('sanctum')->user();

        if (!$client instanceof Client) {
            return 0;
        }

        return Booking::query()
            ->where('client_id', $client->id)
            ->whereIn('status', BookingStatusEnum::currentTabStatuses())
            ->count();
    }

    protected function inquiriesCount(): int
    {
        $client = auth('sanctum')->user();

        if (!$client instanceof Client) {
            return 0;
        }

        return Inquiry::query()
            ->where('client_id', $client->id)
            ->where('status', InquiryStatusEnum::PENDING)
            ->count();
    }
}
