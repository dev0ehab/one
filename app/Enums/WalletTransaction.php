<?php

namespace App\Enums;

/**
 * Class WalletTransaction
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class WalletTransaction extends Base
{
    public const CHARGE   = 0;
    public const DEBT     = 1;
    public const PAYORDER = 2;
}
