<?php

namespace App\Services\PaymentGateway;

class PaypalService
{
    public function createPayment()
    {
        return 'createPayment paypal';
    }

    public function getCustomerInfo()
    {
        return 'get customer info paypal';
    }

    public function getPaymentTransactionInfo()
    {
        return 'get payment transaction info';
    }
    
    public function getShippingAddress()
    {
        return 'get shipping address';
    }
}