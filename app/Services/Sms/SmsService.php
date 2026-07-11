<?php

namespace App\Services\Sms;

use App\Models\SMS;
use App\Services\Sms\ZainService;
use App\Services\Sms\HismsService;
use App\Services\Sms\JawalyService;
use App\Services\Sms\MsegatService;
use App\Services\Sms\OursmsService;
use App\Services\Sms\GatewayService;
use App\Services\Sms\YamamahService;
use App\Services\Sms\UnifonicService;
use App\Services\Sms\TaqnyatService;


class SmsService
{

    public function sendSms($phone, $msg)
    {
        $data = SMS::where(['active' => 1])->first();

        switch ($data->key) {
            case 'Yamamah':
                (new YamamahService())->send($phone, $msg, $data);
                break;
            case 'Jawaly':
                (new JawalyService())->send($phone, $msg, $data);
                break;
            case 'Gateway':
                (new GatewayService())->send($phone, $msg, $data);
                break;
            case 'Hisms':
                (new HismsService())->send($phone, $msg, $data);
                break;
            case 'Msegat':
                (new MsegatService())->send($phone, $msg, $data);
                break;
            case 'Oursms':
                (new OursmsService())->send($phone, $msg, $data);
                break;
            case 'Unifonic':
                (new UnifonicService())->send($phone, $msg, $data);
                break;
            case 'Zain':
                (new ZainService())->send($phone, $msg, $data);
                break;
            case 'Taqnyat':
                (new TaqnyatService())->send($phone, $msg, $data);
                break;
        }
    }
}
