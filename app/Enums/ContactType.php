<?php

namespace App\Enums;

class ContactType extends Base
{
    public const CONTACT    = 'contact';
    public const COMPLAINT  = 'complaint';
    public const SUGGESTION = 'suggestion';

    public static function getRandomValue()
    {
        $arr = [
            self::CONTACT,
            self::COMPLAINT,
            self::SUGGESTION,
        ];

        return $arr[array_rand($arr)];
    }
}
