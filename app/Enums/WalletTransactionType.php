<?php

namespace App\Enums;

/**
 * Class ContactType
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class WalletTransactionType extends Base
{
    public const CHARGE  = 'charge';
    public const WITHDRAW = 'withdraw';
    public const TRANSFER = 'transfer';
    public const ORDER   = 'order';
    public const BOOKING = 'booking';
    public const CASHBACK = 'cashback';
    public const LOYALTY = 'loyalty';

    public static function getRandomValue()
    {
        $arr = [
            self::CHARGE,
            self::WITHDRAW,
            self::TRANSFER,
            self::ORDER,
            self::CASHBACK,
            self::LOYALTY
        ];
        
        return $arr[array_rand($arr)];
    }
}
