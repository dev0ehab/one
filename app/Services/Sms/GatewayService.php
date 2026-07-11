<?php

namespace App\Services\Sms;

use App\Models\SMS;


class GatewayService
{


    public function send($phone, $msg ,$data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $contextPostValues = http_build_query(array(
            'user'     => $username,
            'password' => $password,
            'msisdn'   => $phone,
            'sid'      => $sender,
            'msg'      => $msg,
            'fl'       => 0,
        ));
        $contextOptions['http'] = array(
            'method'           => 'POST',
            'header'           => 'Content-type: application/x-www-form-urlencoded',
            'content'          => $contextPostValues,
            'max_redirects'    => 0,
            'protocol_version' => 1.0,
            'timeout'          => 10,
            'ignore_errors'    => TRUE,
        );
        $contextResouce = stream_context_create($contextOptions);
        $url            = "apps.gateway.sa/vendorsms/pushsms.aspx";
        $arrayResult    = file($url, FILE_IGNORE_NEW_LINES, $contextResouce);
        $result         = $arrayResult[0];
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
