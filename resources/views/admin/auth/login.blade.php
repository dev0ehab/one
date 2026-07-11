<!DOCTYPE html>
<html class="loading login-page-root" lang="{{ app()->getLocale() }}" data-textdirection="{{ lang() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('site.login') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('storage/images/settings/fav_icon.png') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/images/settings/fav_icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/toastr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/login-intro-toast.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/premium-theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/login-merchant.css') }}">

    <style>
        :root {
            --main: {{ $data['color'] ?? '#475569' }};
            --hover: {{ $data['hover_color'] ?? '#334155' }};
            --login-brand-start: #0f172a;
            --login-brand-end: #1e293b;
        }

        html, body.login-page-premium {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        .login-premium-toggle-pw {
            position: absolute;
            bottom: 0.55rem;
            inset-inline-end: 0.65rem;
            border: none;
            background: transparent;
            color: #94a3b8;
            width: 2rem;
            height: 2rem;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .login-premium-toggle-pw:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .login-premium-remember {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1.25rem;
        }

        .text-danger-inline {
            display: block;
            color: #ef4444;
            font-size: 0.8125rem;
            margin-top: 0.35rem;
        }

        .border-danger {
            border-color: #ef4444 !important;
        }
    </style>
</head>
<body class="login-page-premium vertical-layout blank-page" data-col="1-column">
    {{-- Floating merchant marketplace icons (products, produce, tools, machines) --}}
    <div class="login-merchant-bg" aria-hidden="true">
        <i class="fa-solid fa-carrot login-merchant-icon" style="--x:8%;--y:12%;--size:2rem;--rotate:-12deg;--duration:26s;--delay:0s"></i>
        <i class="fa-solid fa-apple-whole login-merchant-icon" style="--x:22%;--y:78%;--size:1.6rem;--rotate:8deg;--duration:22s;--delay:-4s"></i>
        <i class="fa-solid fa-scissors login-merchant-icon" style="--x:75%;--y:18%;--size:1.85rem;--rotate:25deg;--duration:28s;--delay:-8s"></i>
        <i class="fa-solid fa-gears login-merchant-icon" style="--x:88%;--y:55%;--size:2.1rem;--rotate:0deg;--duration:30s;--delay:-2s"></i>
        <i class="fa-solid fa-box login-merchant-icon" style="--x:42%;--y:8%;--size:1.5rem;--rotate:-6deg;--duration:24s;--delay:-6s"></i>
        <i class="fa-solid fa-store login-merchant-icon" style="--x:55%;--y:88%;--size:2rem;--rotate:5deg;--duration:27s;--delay:-10s"></i>
        <i class="fa-solid fa-truck login-merchant-icon" style="--x:12%;--y:48%;--size:1.75rem;--rotate:-8deg;--duration:25s;--delay:-3s"></i>
        <i class="fa-solid fa-shirt login-merchant-icon" style="--x:92%;--y:82%;--size:1.55rem;--rotate:15deg;--duration:23s;--delay:-7s"></i>
        <i class="fa-solid fa-wheat-awn login-merchant-icon" style="--x:35%;--y:62%;--size:1.9rem;--rotate:-18deg;--duration:29s;--delay:-5s"></i>
        <i class="fa-solid fa-shopping-bag login-merchant-icon" style="--x:68%;--y:38%;--size:1.65rem;--rotate:10deg;--duration:21s;--delay:-9s"></i>
        <i class="fa-solid fa-seedling login-merchant-icon" style="--x:48%;--y:42%;--size:1.7rem;--rotate:-4deg;--duration:26s;--delay:-1s"></i>
        <i class="fa-solid fa-hammer login-merchant-icon" style="--x:28%;--y:28%;--size:1.45rem;--rotate:20deg;--duration:24s;--delay:-11s"></i>
        <i class="fa-solid fa-basket-shopping login-merchant-icon" style="--x:62%;--y:72%;--size:1.8rem;--rotate:-10deg;--duration:28s;--delay:-4s"></i>
        <i class="fa-solid fa-clipboard-list login-merchant-icon" style="--x:82%;--y:28%;--size:1.5rem;--rotate:6deg;--duration:22s;--delay:-6s"></i>
        <i class="fa-solid fa-lemon login-merchant-icon" style="--x:5%;--y:85%;--size:1.55rem;--rotate:-15deg;--duration:27s;--delay:-8s"></i>
        <i class="fa-solid fa-industry login-merchant-icon" style="--x:78%;--y:68%;--size:1.95rem;--rotate:0deg;--duration:31s;--delay:-2s"></i>
    </div>

    <div class="login-premium-shell">
        <aside class="login-premium-brand" aria-hidden="false">
            <div class="login-merchant-bg login-merchant-bg--brand" aria-hidden="true">
                <i class="fa-solid fa-carrot login-merchant-icon" style="--x:15%;--y:20%;--size:2.5rem;--rotate:-8deg;--duration:32s"></i>
                <i class="fa-solid fa-scissors login-merchant-icon" style="--x:80%;--y:25%;--size:2.2rem;--rotate:18deg;--duration:28s;--delay:-5s"></i>
                <i class="fa-solid fa-gears login-merchant-icon" style="--x:70%;--y:75%;--size:2.8rem;--rotate:0deg;--duration:35s;--delay:-10s"></i>
                <i class="fa-solid fa-store login-merchant-icon" style="--x:25%;--y:70%;--size:2.4rem;--rotate:5deg;--duration:30s;--delay:-3s"></i>
                <i class="fa-solid fa-box login-merchant-icon" style="--x:50%;--y:45%;--size:2rem;--rotate:-12deg;--duration:26s;--delay:-7s"></i>
                <i class="fa-solid fa-truck login-merchant-icon" style="--x:90%;--y:50%;--size:2.1rem;--rotate:-5deg;--duration:29s;--delay:-1s"></i>
            </div>
            <div class="login-premium-brand-content">
                <img class="auth-logo" src="{{ $data['logo'] }}" alt="{{ $data['name_' . lang()] }}">
                <h2 class="login-premium-brand-title">{{ $data['name_' . lang()] }}</h2>
                <p class="login-premium-brand-desc">{{ __('site.hi') }} {{ $data['name_' . lang()] }}</p>
            </div>
        </aside>

        <main class="login-premium-form-panel">
            <div class="login-premium-card">
                <div class="login-premium-mobile-logo d-lg-none">
                    <img src="{{ $data['logo'] }}" alt="">
                </div>

                <h1>{{ __('site.login') }}</h1>
                <p class="login-premium-subtitle">{{ __('site.hi') }} {{ $data['name_' . lang()] }}</p>

                <form class="form-horizontal parsley login-premium-form" action="{{ route('admin.login') }}" method="post" novalidate>
                    @csrf

                    <div class="login-premium-field">
                        <label for="login-email">{{ __('site.email') }}</label>
                        <i class="fa fa-envelope login-premium-field-icon"></i>
                        <input type="email" id="login-email" class="form-control" name="email"
                            autocomplete="email" required placeholder="{{ __('site.email') }}"
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}"
                            data-parsley-type-message="{{ __('admin.email_formula_is_incorrect') }}">
                    </div>

                    <div class="login-premium-field">
                        <label for="user-password">{{ __('site.password') }}</label>
                        <i class="fa fa-lock login-premium-field-icon"></i>
                        <input type="password" class="form-control" name="password" id="user-password"
                            autocomplete="current-password" required placeholder="{{ __('site.password') }}"
                            data-parsley-required-message="{{ __('admin.this_field_is_required') }}"
                            data-parsley-minlength="6"
                            data-parsley-minlength-message="{{ __('admin.this_field_must_be_at_least', ['min' => 6]) }}">
                        <button type="button" id="toggle-password" class="login-premium-toggle-pw" aria-label="{{ __('site.password') }}">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>

                    <label class="login-premium-remember">
                        <input type="checkbox" name="remember">
                        <span>{{ __('site.remember') }}</span>
                    </label>

                    <button type="submit" class="login-premium-submit submit_button neo-btn">
                        {{ __('site.login') }}
                    </button>
                </form>
            </div>
        </main>
    </div>

    <script src="{{ asset('admin/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.2/dist/parsley.js"></script>
    <script src="{{ asset('admin/assets/js/premium-ui.js') }}"></script>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            var passwordField = document.getElementById('user-password');
            var icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        toastr.options = Object.assign({}, toastr.options || {}, {
            closeButton: true,
            progressBar: true,
            newestOnTop: true,
            positionClass: 'toast-top-right',
            preventDuplicates: true,
            timeOut: 3600,
            rtl: {{ lang() == 'ar' ? 'true' : 'false' }},
            toastClass: 'toast intro-toast'
        });

        $(document).ready(function () {
            $('.parsley').parsley();

            $(document).on('submit', '.form-horizontal', function (e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var $btn = $(this).find('.submit_button');

                $.ajax({
                    url: url,
                    method: 'post',
                    data: new FormData($(this)[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        if (window.neoSetButtonLoading) {
                            window.neoSetButtonLoading($btn, true);
                        } else {
                            $btn.prop('disabled', true);
                        }
                    },
                    success: function (response) {
                        $('.text-danger-inline').remove();
                        $('.form-horizontal input').removeClass('border-danger');

                        if (response.status == 'login') {
                            toastr.success(response.message);
                            setTimeout(function () {
                                window.location.replace(response.url);
                            }, 1000);
                        } else {
                            if (window.neoSetButtonLoading) {
                                window.neoSetButtonLoading($btn, false);
                            } else {
                                $btn.prop('disabled', false);
                            }
                            $btn.text('{{ __('site.login') }}');
                            $('.form-horizontal input[name=password]').addClass('border-danger');
                            $('.form-horizontal input[name=password]').after(
                                '<span class="text-danger-inline">' + response.message + '</span>'
                            );
                        }
                    },
                    error: function (xhr) {
                        if (window.neoSetButtonLoading) {
                            window.neoSetButtonLoading($btn, false);
                        } else {
                            $btn.prop('disabled', false).text('{{ __('site.login') }}');
                        }
                        $('.text-danger-inline').remove();
                        $('.form-horizontal input').removeClass('border-danger');

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function (key, value) {
                                $('.form-horizontal input[name=' + key + ']').addClass('border-danger');
                                $('.form-horizontal input[name=' + key + ']').after(
                                    '<span class="text-danger-inline">' + value + '</span>'
                                );
                            });
                        }
                    },
                });
            });
        });
    </script>
</body>
</html>
