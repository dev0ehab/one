<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{-- <meta name="description" content="{{setting('site_description')}}" /> --}}
    {{-- <meta name="keywords" content="{{setting('site_tagged')}}" /> --}}
    <meta name="author" content="mohamed maree m7mdmaree26@gmail.com" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    {{-- <link rel="shortcut icon" href="{{asset('dashboard/uploads/setting/site_logo/'.setting('site_logo'))}}"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/bootstrap-select.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/style.css')}}" rel="stylesheet" />
    <script src="{{asset('js/jquery-1.11.2.min.js')}}"></script>
    {{-- <title>{{ setting('site_title') }}</title> --}}
    <style>
    
    body{
        display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 15px;
    }
    
        .up-label .tit{
            font-weight: bold;
        }
        .form .btn,
        .wpwl-button-pay{
        }
        .app-title{
        }
        .wpwl-button-pay {
            display: block;
            margin: auto;
            float: unset;
            width: 100%;
        }

        .wpwl-form {
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 10px 0;
        }
        @media (min-width: 480px) {
            .wpwl-form-card .wpwl-group-cardNumber, .wpwl-form-card .wpwl-group-cardHolder, .wpwl-form-card .wpwl-group-birthDate, .wpwl-form-card .wpwl-group-brand-v2 ,
            .wpwl-form-card .wpwl-group-expiry, .wpwl-group-brandLogo, .wpwl-form-card .wpwl-group-cvv{
                padding-left:0;
                width: 100%;
            }
        }
        .wpwl-label-brand, .wpwl-wrapper-brand{
            padding-left: 0;
        }
        .wpwl-group, .wpwl-label-brand, .wpwl-label-brand-v2, .wpwl-wrapper-brand, .wpwl-wrapper-brand-v2, .wpwl-wrapper-registration{
            float: unset;
        }
        .wpwl-brand-card {
            float: unset;
            position: absolute;
            top: 0;
            right: 0;
        }
        .wpwl-button-pay,
        .form .form-control:focus{
        }
        .form .btn-send:hover,
        .wpwl-button-pay:hover{
            background: #fff;
        }
        iframe[name='card.cvv'],iframe[name='card.number']{
            direction: ltr;
        }
        .wpwl-label-brand,
        .wpwl-label{
            font-weight: bold;
            margin-bottom: 7px;
        }
        .a-s{
            background: #fff;
            padding: 0;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .pay_img{
    width: 100%;
    border-radius: 5px;
    height: 60px;
    max-width: 100%;
    object-fit: contain;
        }
        .content-login{
    width: 500px;
    max-width: 100%;
    margin: auto;
    text-align: center;
    position: relative;
            
        }
        .payment-card{
    border: 1px solid #b86f9b;
    padding: 5px;
    border-radius: 10px;
    transition: .3s all ease;
    flex: 1;
        }
        
        .payment-card a{
            display: block;
        }
        
        .payment-card:hover{
            box-shadow: 0px 0px 5px 0px #b86f9b;
            transform:translateY(-1px);
        }
        .logo img{
            width: 150px;
        }
        
        .pay-link{
            display: flex;
            gap: 10px
        }
        
        
        .btnBack_{
            position: absolute;
            top: 0;
            right: 0;
            width: 40px;
            height: 40px;
            background: #eeeeee87;
            border: 1px solid #bd729f;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            color: #bd729f !important;
            text-decoration: none !important;
        }
        
        
        /*body{*/
        /*    min-height: 100vh;*/
        /*}*/
    </style>
</head>
<body>
    
    <div class="container">
     
            <aside class="content-login">
                <div class="logo mb-4">
                    <img src="{{asset('/storage/images/settings/logo.png')}}" />
                </div>
                
                <!--<div class="text-center my-4">-->
                <!--    <h5>@yield('head')</h5>-->
                <!--</div>-->
                <div>
                @yield('content')
                @if (session('successmsg'))
                <div class="text-success a-s" role="alert">
                    {{ session('successmsg') }}
                </div>
                @elseif(session('msg'))
                <div class=" text-danger a-s" role="alert">
                    {{ session('msg') }}
                </div>
                @endif
            </div>
            </aside>
            
    </div>


    <img class="fixed-img" src="https://marsol-cs.4hoste.com/dashboard/images/undraw_walk_in_the_city_1ma6.png" alt="">

    <!-- End Content -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('js/scripts.js')}}"></script>

</body>
</html>
