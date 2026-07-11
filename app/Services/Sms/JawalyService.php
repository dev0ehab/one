<?php
namespace App\Services\Sms;
use App\Models\SMS;
class JawalyService
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $text     = urlencode($msg);
        $sender   = urlencode($data->sender_name);

        $url    = "https://www.4jawaly.net/api/sendsms.php?username=$username&password=$password&numbers=$phone&sender=$sender&message=$text&unicode=e&Rmduplicated=1&return=string";
        $result = file_get_contents($url, true);
    }
}
