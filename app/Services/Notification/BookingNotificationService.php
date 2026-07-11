<?php

namespace App\Services\Notification;

use App\Enums\NotificationTypeEnum;
use App\Models\Booking;

class BookingNotificationService
{
    public static function notifyPaid(Booking $booking): void
    {
        $booking->loadMissing('provider');

        if ($booking->provider) {
            UserNotifier::send(
                $booking->provider,
                NotificationTypeEnum::BookingPaid,
                $booking,
                $booking->id,
                ['order_num' => $booking->booking_number]
            );
        }
    }

    public static function notifyClient(Booking $booking, string $type, array $replace = []): void
    {
        $booking->loadMissing('client');

        if (!$booking->client) {
            return;
        }

        UserNotifier::send(
            $booking->client,
            $type,
            $booking,
            $booking->id,
            array_merge(['order_num' => $booking->booking_number], $replace)
        );
    }

    public static function notifyProvider(Booking $booking, string $type, array $replace = []): void
    {
        $booking->loadMissing('provider');

        if (!$booking->provider) {
            return;
        }

        UserNotifier::send(
            $booking->provider,
            $type,
            $booking,
            $booking->id,
            array_merge(['order_num' => $booking->booking_number], $replace)
        );
    }
}
