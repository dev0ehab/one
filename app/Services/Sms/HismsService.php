<?php
namespace App\Services\Sms;
use App\Models\SMS;

class HismsService {
    public function send($phone, $msg ,$data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = urlencode($data->sender_name);
        $msg      = urlencode($msg);

        $url    = "https://www.hisms.ws/api.php?send_sms&username=$username&password=$password&numbers=$phone&sender=$sender&message=$msg";
        $result = file_get_contents($url, true);
        if (false === $result) {
            return false;
        } else {
            return true;
        }
    }
}