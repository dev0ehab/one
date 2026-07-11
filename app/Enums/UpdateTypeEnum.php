<?php

namespace App\Enums;

/**
 * Class CouponTypeEnum
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class UpdateTypeEnum extends Base
{
    const UPDATE = 'update';
    const DELETE = 'delete';
}
