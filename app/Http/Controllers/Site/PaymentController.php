<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function getHyperPay($transaction_id, $brand_id, $brand_type)
    {
        return view('payment.hyperpay.index', compact('transaction_id', 'brand_id', 'brand_type'));
    }
}
