<?php

namespace App\Enums;

/**
 * Class OTPThrough
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class OTPThrough extends Base
{
    public const PHONE = 'phone';
    public const EMAIL = 'email';
}
