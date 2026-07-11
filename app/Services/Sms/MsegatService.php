<?php

namespace App\Services\Sms;

use App\Models\SMS;

class MsegatService
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.msegat.com/gw/sendsms.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);

        curl_setopt($ch, CURLOPT_POST, TRUE);

        $fields = <<<EOT
        {
        "userName": "$username",
        "numbers": "$phone",
        "userSender": "$sender",
        "apiKey": "$password",
        "msg": "$msg"
        }
        EOT;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));

        $response = curl_exec($ch);
        $info     = curl_getinfo($ch);
        curl_close($ch);

        return true;
    }
}
