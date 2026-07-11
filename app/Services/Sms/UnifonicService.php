<?php
namespace App\Services\Sms;
use App\Models\SMS;
class UnifonicService
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;

        $sender = urlencode($data->sender_name);

        $numbers = explode(',', $phone);
        $text    = urlencode($msg);
        $url     = "http://api.unifonic.com/wrapper/sendSMS.php?userid=$username&password=$password&msg=$text&sender=$sender&to=$numbers&encoding=UTF8";
        $result  = @file_get_contents($url, true);
        if (false === $result) {
            return false;
        } else {
            return true;
        }
    }
}
