<?php

namespace App\Services\PaymentGateway;

use Illuminate\Http\Request;


use App\Traits\LogException;
use App\Contracts\PaymentGateway;
use Illuminate\Support\Facades\Log;
use App\Traits\ConsumesExternalService;
use App\Models\PaymentBrand;
use App\Contracts\AbstractPaymentGateway;
use GuzzleHttp\Exception\GuzzleException;

class  HyperpayService 
{
    use ConsumesExternalService , LogException;

    protected $API_TOKEN;
    protected $NAME;
    protected $MODE;
    protected $base_url;
    protected $apiVersion;


    public function __construct()
    {
        $this->NAME       = 'Hyperpay';
        $this->MODE       = config('payments.' . $this->NAME . '.MODE');
        $this->API_TOKEN  = config('payments.' . $this->NAME . '.'.$this->MODE.'_API_TOKEN');
        $this->base_url   = config('payments.' . $this->NAME . '.'.$this->MODE.'_BASE_URL');
        $this->apiVersion = config('payments.' . $this->NAME . '.VERSION') ??  null;
    }

    public function createPayment($request , $user)
    {
        $brand = PaymentBrand::findOrFail($request['brand_id']);
//        dd($brand);
        $data = self::getPaymentTransactionInfo($request['amount'], $user ,$brand->entity_id);
        // try {
            $payemtResponse =  $this->makeRequest(
                'POST',
                $this->base_url . 'v1/checkouts',
                $data,
                [],
                $this->getHyperpayParams(),
                true
            );
            $responseData = json_decode($payemtResponse);
            $checkoutId   = $responseData->ndc;
            return [
                'payment_name'     => $this->NAME, 
                'paymentResponse'  => $payemtResponse,
                'transaction_id'   => $checkoutId,
                'redirect_url' => route('getHyperPay', ['transaction_id' => $checkoutId , 'brand_id' => $brand->id , 'brand_type' => $brand->type]),
            ];

        // } catch (GuzzleException $exception) {
        //    return $this->logMethodException($exception);
        // }
    }

    private function getHyperpayParams()
    {
        return [
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer OGFjN2E0Yzg4ODAyOGE2YzAxODgwNGIyMWM0ZjAyOTN8ZTNjbndoajhweQ==',
        ];
    }

    public function getPaymentTransactionInfo($amount , $user , $entity_id)
    {
        return [
            'entityId'              => $entity_id,
            'amount'                => $amount ,
            'merchantTransactionId' => rand(1111, 9999) . $user->id,
            'currency'              => $user->currency ?? 'SAR' ,
            'customer.email'        => $user->email ?? 'user@gmail.com',
            'customer.surname'      => $user->name ?? 'ahmed abdullah',
            'customer.givenName'    => $user->name ?? 'ahmed abdullah',
            'billing.street1'       => $user->street ?? 'Prince Badr bin Abdulaziz Street',
            'billing.country'       => $user->country->name ?? 'SA',
            'billing.state'         => $user->state->name ?? 'state',
            'billing.city'          => $user->city->name ?? 'Riyadh',
            'billing.postcode'      => $user->postcode ?? '21955',
            'paymentType'           => 'DB',
        ];
    }

    public function getPaymentStatus($request , $brand_id)
    {
        $id = request()->resourcePath;
//        $id = trim($id , '/');
        $brand = PaymentBrand::findOrFail($brand_id)->firstOrFail();
//        try{
            $response = $this->makeRequest(
                'GET',
                $this->base_url . $id ,
                ['entityId' => $brand->entity_id],
                [],
                $this->getHyperpayParams(),
                true);

            $responseData = json_decode($response, true);
            $code         = isset($responseData['result']['code']) ? $responseData['result']['code'] : '-1';
            return [
                'status'         => $this->is_success($code) == false  ? 0 : 1 ,
                'transaction_id' => $responseData['ndc'] ,
            ];
//        } catch (GuzzleException $exception) {
//            Log::emergency("File:" . $exception->getFile(). "Line:" . $exception->getLine(). "Message:" . $exception->getMessage());
//            return [
//                'code' => $exception->getCode(),
//                'message' => $exception->getMessage()
//            ];
//            return $this->failMsg(__("messages.something_went_wrong"));
//        }
    }

    public function is_success( $code )
    {
        if(preg_match("/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0|000.400.0[^3]|000.400.100)/", $code)){

            return true;
        }
        return false;
    }

}