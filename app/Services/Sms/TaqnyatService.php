<?php
namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
class TaqnyatService    
{
    public function send($phone,$msg, $data)
    {
        $username = $data->user_name;
        $password = $data->password;
        $sender   = $data->sender_name;

        $text = urlencode($msg);

        $url = "https://api.taqnyat.sa//v1/messages";
    
        $data = [
            "recipients" => [$phone],
            "body"       => $text,
            "sender"     => $sender,
        ];


        $response = Http::withHeaders([
            "Authorization" => "Bearer " . $password,
            "Content-Type" => "application/json",
        ])->post($url, $data);

        dd($response->json());

        if ($response->successful()) {
            return $response->json();
        } else {
            return $response->json();
        }


    }
}
