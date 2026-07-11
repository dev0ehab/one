@extends('payment.hyperpay.paymentLayout')
@section('content')
    <style>
        .wpwl-container{
            z-index: 99;
            color : rgb(15, 13, 13);
        }
        .wpwl-control, .wpwl-group-registration{
            color:#333;
        }
        .pay-link{
            position: relative;
            z-index: 99;
        }
        .pay-link ul{
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pay-link ul li{
            margin: 10px;
        }
        .pay-link ul li img{
            width: 120px;
            height: 50px;
            max-width: 100%;
        }
    </style>
    
    <a href="{{url()->previous()}}" class="btnBack_"><i class="fa fa-arrow-right" aria-hidden="true"></i></a>
    
    <form action="{{route('payment.getPaymentStatus' , ['brand_id' => $brand_id]) }}" class="paymentWidgets" data-brands="{{$brand_type}}"></form>
    <script>
        var wpwlOptions = {
            locale: "ar",
        }
    </script>
    {{-- @if(setting('hyperpay_status') && setting('hyperpay_status') == 'live') --}}
        {{-- <script src="https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkoutId}}"></script> --}}
    {{-- @else --}}
        <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$transaction_id}}"></script>
    {{-- @endif --}}

@endsection
