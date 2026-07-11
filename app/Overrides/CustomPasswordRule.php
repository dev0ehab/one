<?php

namespace App\Overrides;

use Illuminate\Validation\Rules\Password;


class CustomPasswordRule extends Password
{
    
  /**
   * Get the translated password error message.
   *
   * @param  string  $key
   * @return string
   */
  protected function getErrorMessage($key)
  {
    if (($message = trans($key)) !== $key) {
      return $message;
    }

    $messages = [
      'validation.password.mixed'         => __("validation.password-mixed"),
      'validation.password.letters'       => __("validation.password-letters"),
      'validation.password.symbols'       => __("validation.password-symbols"),
      'validation.password.numbers'       => __("validation.password-numbers"),
      'validation.password.uncompromised' => __("validation.password-uncompromised"),
    ];

    return $messages[$key];
  }
}