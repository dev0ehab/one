<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="apple-touch-icon" href="{{ Cache::get('settings')['fav_icon'] }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ Cache::get('settings')['fav_icon'] }}">

    <title>
        @yield('title', isset(\Request::route()->getAction()['title']) ? __('routes.' . \Request::route()->getAction()['title']) : '')
    </title>

    @if (lang() == 'ar')
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/vendors-rtl.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/core/colors/palette-gradient.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/pages/dashboard-ecommerce.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/pages/card-analytics.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/custom-rtl.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style-rtl.css') }}">
    @else
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/vendors.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap-extended.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/colors.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/components.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/semi-dark-layout.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/core/colors/palette-gradient.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/dashboard-ecommerce.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/card-analytics.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/custom-rtl.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style_en.css') }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/flatpickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/plugins/extensions/toastr.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>


    <style>
        @php
            $__adminBrand = \Illuminate\Support\Facades\Cache::get('settings') ?? [];
        @endphp
        /* Brand vars for Toastr intro-toast (same tokens as intro landing); defaults match NeoTech slate accent */
        body.admin-neotech {
            --main: {{ $__adminBrand['color'] ?? '#475569' }};
            --hover: {{ $__adminBrand['hover_color'] ?? '#334155' }};
        }

        .cke_notifications_area {
            display: none !important;
        }

        .textDanger {
            color: #EA5455 !important;
        }

        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success {
            color: #065f46;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
        }

        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            color: #991b1b;
            background-color: #fef2f2;
            border: 1px solid #fecaca;
        }

        .parsley-errors-list {
            margin: 4px 0 0;
            padding: 0;
            list-style-type: none;
            font-size: 0.8125rem;
            line-height: 1.3;
            opacity: 0;
            color: #ef4444;
            transition: opacity 0.2s ease;
        }

        .parsley-errors-list.filled {
            opacity: 1;
        }
    </style>
    @if (lang() == 'ar')
        <style>
            .text_start_left {
                direction: ltr;
                text-align: end;
            }
        </style>
    @endif
    @yield('css')
    @stack('css')
    {{-- Premium theme must load after page/stack CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/admin-neotech-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/premium-theme.css') }}">
</head>

<body style="font-family: 'Inter', 'Tajawal', sans-serif !important;font-weight:500;" id="content_body" class="admin-neotech position-relative vertical-layout vertical-menu-modern 2-columns  navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
{{-- loader: cartoon rocket straight ascent --}}
<div class="loader neo-rocket-loader" id="neo-page-loader" role="status" aria-label="{{ __('admin.loading') ?? 'Loading' }}">
    <div class="neo-rocket-loader__scene" aria-hidden="true">
        <div class="neo-rocket-loader__rocket">
            <div class="neo-rocket-loader__spin">
                <span class="neo-rocket-loader__nose"></span>
                <span class="neo-rocket-loader__hull">
                    <span class="neo-rocket-loader__stripe"></span>
                    <span class="neo-rocket-loader__panel"></span>
                    <span class="neo-rocket-loader__visor"></span>
                    <span class="neo-rocket-loader__fin neo-rocket-loader__fin--l"></span>
                    <span class="neo-rocket-loader__fin neo-rocket-loader__fin--r"></span>
                </span>
                <span class="neo-rocket-loader__thruster"></span>
                <span class="neo-rocket-loader__trail">
                    <span class="neo-rocket-loader__beam"></span>
                    <span class="neo-rocket-loader__beam"></span>
                    <span class="neo-rocket-loader__beam"></span>
                </span>
            </div>
        </div>
    </div>
</div>
