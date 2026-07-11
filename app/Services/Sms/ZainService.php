<?php

namespace App\Services\Sms;

use App\Models\SMS;

class ZainService
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $to   = $phone; // Should be like 966530007039
        $text = urlencode($msg . '   ');

        $link = "https://www.zain.im/index.php/api/sendsms/?user=$username&pass=$password&to=$to&message=$text&sender=$sender";

        if (function_exists('curl_init')) {
            $curl = @curl_init($link);
            @curl_setopt($curl, CURLOPT_HEADER, FALSE);
            @curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            @curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            @curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            $source = @curl_exec($curl);
            @curl_close($curl);
            if ($source) {
                return $source;
            } else {
                return @file_get_contents($link);
            }
        } else {
            return @file_get_contents($link);
        }
    }
}
