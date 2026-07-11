<!doctype html>
<html lang="{{ lang() }}" dir="{{ lang() == 'ar' ? 'rtl' : 'ltr' }}" style="scroll-behavior: smooth">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('/intro_site/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/intro_site/css/all.min.css')}}">
    <!-- plugins CSS -->
    <link rel="stylesheet" href="{{asset('/intro_site/plugins/owl-carousel/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('intro_site/css/intro-tokens.css')}}">
    <!-- my CSS -->
    <link rel="stylesheet" href="{{asset('intro_site/css/style.css')}}">
    @if (lang() == 'en')
        <link rel="stylesheet" href="{{asset('intro_site/css/style-ltr.css')}}">
    @endif

    <meta name="description" content="{{$settings['intro_meta_description']}}" />
    <meta name="keywords" content="{{$settings['intro_meta_keywords']}}" />

    <meta property="fb:app_id" content="140586622674265" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ Request::url() }}" />

    <meta property="og:title" content="{{$settings['intro_name']}}" />
    <meta property="og:description" content="{{$settings['intro_meta_description']}}" />
    <meta property="og:image" content="{{asset($settings['intro_logo'])}}"/>
    <meta property="og:width" content="200"/>
    <meta property="og:height" content="200"/>

    <style>
        :root {
            --main: {{$settings['color']}};
            --hover: {{$settings['hover_color']}};
            --main2: linear-gradient(to right bottom, {{$settings['buttons_color']}}, {{$settings['buttons_color']}}, {{$settings['buttons_color']}}, {{$settings['buttons_color']}}, {{$settings['buttons_color']}});
            --white: #ffffff;
            --gray: #777;
        }

        .textDanger {
            color: #EA5455 !important;
        }
        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success {
            color: #468847;
            background-color: #DFF0D8;
            border: 1px solid #D6E9C6;
        }
        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            color: #B94A48;
            background-color: #F2DEDE;
            border: 1px solid #EED3D7;
        }
        .parsley-errors-list {
            margin: 2px 0 3px;
            padding: 0;
            list-style-type: none;
            font-size: 0.9em;
            line-height: 0.9em;
            opacity: 0;
            color: #B94A48;
            transition: all .3s ease-in;
        }
        .parsley-errors-list.filled {
            opacity: 1;
        }
    </style>
    <title>{{$settings['intro_name']}}</title>
    <!-- title logo -->
    <link rel="icon"  href="{{asset($settings['intro_logo'])}}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ Cache::get('settings')['fav_icon'] }}">
</head>
