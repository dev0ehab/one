<?php

namespace App\Enums;

class BookingStatusEnum extends Base
{
    public const NEW              = 'new';
    public const PENDING_PAYMENT  = 'pending_payment';
    public const IN_PROGRESS      = 'in_progress';
    public const COMPLETED        = 'completed';
    public const CANCELLED        = 'cancelled';

    public static function currentTabStatuses(): array
    {
        return [
            self::NEW,
            self::PENDING_PAYMENT,
            self::IN_PROGRESS,
        ];
    }
}
