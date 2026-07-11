<?php

namespace App\Enums;

/**
 * Class PaymentTransactions
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class PaymentTransactions extends Base
{
    public const CHARGEWALLET    = 0;
    public const PAYORDER        = 1;
    public const PAYRESERVATION  = 2;
    public const PAYBOOKING             = 3;
    public const PAYADDITIONALINVOICE   = 4;
}
