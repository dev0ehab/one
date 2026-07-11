<?php

namespace App\Services\Sms;

use App\Models\SMS;


class YamamahService
{
    public function send($phone, $msg , $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $url    = 'api.yamamah.com/SendSMS';
        $to     = $phone; // Should be like 966530007039
        $text   = urlencode($msg);
        $fields = array(
            "Username"        => $username,
            "Password"        => $password,
            "Message"         => $text,
            "RecepientNumber" => $to, //'00966'.ltrim($numbers,'0'),
            "ReplacementList" => "",
            "SendDateTime"    => "0",
            "EnableDR"        => False,
            "Tagname"         => $sender,
            "VariableList"    => "0",
        );

        $fields_string = json_encode($fields);

        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_POST           => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
            ),
            CURLOPT_POSTFIELDS     => $fields_string,
        ));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
