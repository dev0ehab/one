<?php

namespace App\Services\PaymentGateway;

use App\Traits\LogException;
use Illuminate\Support\Facades\Log;
use App\Traits\ConsumesExternalService;
use GuzzleHttp\Exception\GuzzleException;

class MyfatoorahService 
{
    use ConsumesExternalService , LogException;

    protected $API_TOKEN;
    protected $NAME;
    protected $MODE;
    protected $base_url;
    protected $apiVersion;

    public function __construct()
    {
        $this->NAME       = 'Myfatoorah';
        $this->MODE       = config('payments.' . $this->NAME . '.MODE');
        $this->API_TOKEN  = config('payments.' . $this->NAME . '.'.$this->MODE.'_API_TOKEN');
        $this->base_url   = config('payments.' . $this->NAME . '.'.$this->MODE.'_BASE_URL');
        $this->apiVersion = config('payments.' . $this->NAME . '.VERSION') ??  null;

        // dd($this->API_TOKEN);
    }

    public function createPayment($request , $user)
    {
        try {
            $data = self::getPaymentTransactionInfo( $request['amount'], $user);
            
            $payemtResponse =  $this->makeRequest(
                'POST',
                '/v2/SendPayment',
                [],
                $data,
                ['Content-Type' => 'application/json','authorization' => 'Bearer ' . $this->API_TOKEN],
                true
            );


            $responseData = json_decode($payemtResponse, true);
            
            // Check if response is successful
            if (!isset($responseData['IsSuccess']) || !$responseData['IsSuccess']) {
                $errorMessage = $responseData['Message'] ?? 'Payment creation failed';
                throw new \Exception($errorMessage);
            }
            
            return [
                'payment_name'     => $this->NAME, 
                'paymentResponse'   => $payemtResponse,
                'transaction_id'   => $responseData['Data']['InvoiceId'],
                'redirect_url'     => $responseData['Data']['InvoiceURL'],
            ];

        } catch (GuzzleException $exception) {
            Log::error('Myfatoorah Payment Error: ' . $exception->getMessage());
            throw new \Exception('Payment gateway error: ' . $exception->getMessage());
        } catch (\Exception $exception) {
            Log::error('Myfatoorah Payment Error: ' . $exception->getMessage());
            throw $exception;
        }
    }

    public function getPaymentTransactionInfo($amount , $user): array
    {
        // Clean phone number (remove + and spaces)
        $phone = preg_replace('/[^0-9]/', '', $user->phone ?? '1234567890');
        $countryCode = preg_replace('/[^0-9]/', '', $user->country_code ?? '966');
        
        return [
            'CustomerName'       =>  $user->name ?? 'Customer',
            'NotificationOption' =>  'Lnk',
            'InvoiceValue'       =>  (float) $amount,
            'MobileCountryCode'  =>  $countryCode,
            'CustomerMobile'     =>  $phone,
            'CustomerEmail'      =>  $user->email ?? 'customer@example.com',
            'Language'           =>  $user->lang ?? 'ar',
            'DisplayCurrencyIso' =>  'SAR',
            'CallBackUrl'        =>  route('payment.getPaymentStatus'),
            'ErrorUrl'           =>  route('payment.getPaymentStatus'),
            'CustomerReference'  =>  $user->id ?? 1,
            'UserDefinedField'   =>  (string) ($user->id ?? '1')
        ];
    }

    public function getPaymentStatus($transactionId)
    {
        try {
            $postfields = [
                'Key' => $transactionId,
                'KeyType' => 'paymentId'
            ];
            $response = $this->makeRequest('POST',$this->base_url . '/v2/getPaymentStatus',[],$postfields,['Content-Type' => 'application/json','authorization' => 'Bearer ' . $this->API_TOKEN],true);

            return [
                'status'         => json_decode($response,true)['Data']['InvoiceStatus'] == 'Paid' ? 1 : 0,
                'transaction_id' => json_decode($response,true)['Data']['InvoiceId'] ,
            ];
        } catch (GuzzleException $exception) {
            Log::emergency("File:" . $exception->getFile(). "Line:" . $exception->getLine(). "Message:" . $exception->getMessage());
            return [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];
            return $this->failMsg(__("messages.something_went_wrong"));
        }
    }
    
}