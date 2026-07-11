<?php

namespace App\Enums;

class AdditionalInvoiceStatusEnum extends Base
{
    public const PENDING          = 'pending';
    public const ACCEPTED         = 'accepted';
    public const REJECTED         = 'rejected';
    public const PAID             = 'paid';
    public const PAYMENT_OVERDUE  = 'payment_overdue';

    public static function awaitingPayment(): array
    {
        return [
            self::ACCEPTED,
            self::PAYMENT_OVERDUE,
        ];
    }
}
