<?php
namespace App\Services\Sms;
use App\Models\SMS;
class OursmsService
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $text = urlencode($msg);
        $to   = '+' . $phone;
        // auth call
        //$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=full";
        //لارجاع القيمه json
        $url = "http://www.oursms.net/api/sendsms.php?username=$username&password=$password&numbers=$to&message=$text&sender=$sender&unicode=E&return=json";
        // لارجاع القيمه xml
        //$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E&return=xml";
        // لارجاع القيمه string
        //$url = "http://www.oursms.net/api/sendsms.php?username=$user&password=$password&numbers=$to&message=$text&sender=$sendername&unicode=E";

        // Call API and get return message
        //fopen($url,"r");
        //return $url;
        $ret = file_get_contents($url);
    }
}
