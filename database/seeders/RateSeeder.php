<?php

namespace Database\Seeders;

use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use App\Models\Rate;
use App\Services\Entity\RateService;
use Illuminate\Database\Seeder;

class RateSeeder extends Seeder
{
    public function run(): void
    {
        $booking = Booking::query()
            ->where('status', BookingStatusEnum::COMPLETED)
            ->whereDoesntHave('rate')
            ->first();

        if (!$booking) {
            return;
        }

        Rate::create([
            'client_id'   => $booking->client_id,
            'provider_id' => $booking->provider_id,
            'booking_id'  => $booking->id,
            'rate'        => 5,
            'comment'     => 'خدمة ممتازة وتعامل راقٍ.',
        ]);

        app(RateService::class)->syncProviderAverageRate($booking->provider_id);
    }
}
