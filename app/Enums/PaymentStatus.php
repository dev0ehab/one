<?php

namespace App\Enums;

/**
 * Class PaymentStatus
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class PaymentStatus extends Base
{
    public const PENDING   = "pending";
    public const COMPLETED = "completed";
}
