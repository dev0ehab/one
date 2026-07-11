<?php

namespace App\Services\Entity;

use App\Enums\BookingStatusEnum;
use App\Http\Resources\Api\Provider\Rates\RatesCollection;
use App\Models\Booking;
use App\Models\Provider;
use App\Models\Rate;
use Illuminate\Support\Facades\DB;

class RateService
{
    public function indexApiForProvider(): RatesCollection
    {
        $rates = Rate::where('provider_id', auth()->id())
            ->with('client:id,name,image')
            ->latest('id')
            ->paginate(request('paginate', 10));

        return RatesCollection::make($rates);
    }

    public function rateBooking(Booking $booking, int $clientId, int $stars, ?string $comment = null): array
    {
        if ($booking->client_id !== $clientId) {
            return ['status' => 'fail', 'message' => __('apis.booking_not_found')];
        }

        if ($booking->status !== BookingStatusEnum::COMPLETED) {
            return ['status' => 'fail', 'message' => __('apis.booking_cannot_rate')];
        }

        if (Rate::where('booking_id', $booking->id)->exists()) {
            return ['status' => 'fail', 'message' => __('apis.booking_already_rated')];
        }

        DB::transaction(function () use ($booking, $stars, $comment) {
            Rate::create([
                'client_id'   => $booking->client_id,
                'provider_id' => $booking->provider_id,
                'booking_id'  => $booking->id,
                'rate'        => $stars,
                'comment'     => $comment,
            ]);

            $this->syncProviderAverageRate($booking->provider_id);
        });

        return [
            'status'  => 'success',
            'message' => __('apis.rated'),
        ];
    }

    public function syncProviderAverageRate(int $providerId): void
    {
        $avg = Rate::where('provider_id', $providerId)->avg('rate');

        Provider::where('id', $providerId)->update([
            'avg_rate' => round((float) ($avg ?? 0), 2),
        ]);
    }
}
