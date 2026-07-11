<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LanguageMatchRule implements Rule
{
    public $lang;
    /**
     * Create a new rule instance.
     *
     * @param string $lang
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }


    public function passes($attribute, $value): bool|int
    {
        $value = $this->fixValue($value);
        switch ($this->lang) {
            case 'ar':
                $regex = "/^[\p{Arabic}0-9\s\p{P}\p{S}]+$/u";
                break;
            case 'en':
                $regex = "/^[A-Za-z0-9\s\p{P}\p{S}]+$/u";
                break;
            default:
                return false;
        }
        return (bool) preg_match($regex, $value);
    }

    private function fixValue($value)
    {
        $replaces = [
            "&nbsp;"
        ];

        foreach ($replaces as $replace) {
            $value = str_replace($replace, ' ', $value);
        }

        return trim(strip_tags($value));
    }

    public function message()
    {
        return trans("validation.language_match");
    }

}
