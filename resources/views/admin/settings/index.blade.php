@extends('admin.layout.master')

{{-- extra css files --}}
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css') }}">
@endsection

{{-- extra css files --}}
@section('content')
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">
                <!-- left menu section -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <ul class="nav nav-pills flex-column mt-md-0 mt-1">

                        <li class="nav-item">
                            <a class="nav-link d-flex py-75 active" id="account-pill-main" data-toggle="pill" href="#account-vertical-main" aria-expanded="true">
                                <i class="feather icon-settings mr-50 font-medium-3"></i>
                                {{ __('admin.app_setting') }}
                            </a>
                        </li>


                        <li class="nav-item " style="margin-top: 3px">
                            <a class="nav-link d-flex py-75" id="account-pill-page" data-toggle="pill" href="#account-vertical-page" aria-expanded="false">
                                <i class="feather icon-droplet mr-50 font-medium-3"></i>
                                {{ __('admin.order_wallet_data') }}
                            </a>
                        </li>

                        <li class="nav-item " style="margin-top: 3px">
                            <a class="nav-link d-flex py-75" id="account-pill-api" data-toggle="pill" href="#account-vertical-api" aria-expanded="false">
                                <i class="feather icon-droplet mr-50 font-medium-3"></i>
                                {{ __('admin.api_data') }}
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- right content section -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">

                                    <div class="tab-pane active" id="account-vertical-main" aria-labelledby="account-pill-main" aria-expanded="true">
                                        <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="form-horizontal store" novalidate>
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="type" value="app_setting">

                                            <div class="row">

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.the_name_of_the_application_in_arabic') }}</label>
                                                            <input type="text" class="form-control" name="name_ar" id="account-name" placeholder="{{ __('admin.the_name_of_the_application_in_arabic') }}" value="{{ $data['name_ar'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.the_name_of_the_application_in_english') }}</label>
                                                            <input type="text" class="form-control" name="name_en" id="account-name" placeholder="{{ __('admin.the_name_of_the_application_in_english') }}" value="{{ $data['name_en'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-description">{{ __('admin.the_description_of_the_application_in_arabic') }}</label>
                                                            <textarea type="text" class="form-control" name="description_ar" id="account-description" placeholder="{{ __('admin.the_description_of_the_application_in_arabic') }}">{{ $data['description_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-description">{{ __('admin.the_description_of_the_application_in_english') }}</label>
                                                            <textarea type="text" class="form-control" name="description_en" id="account-description" placeholder="{{ __('admin.the_description_of_the_application_in_english') }}">{{ $data['description_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.email') }}</label>
                                                            <input type="email" class="form-control" name="email" id="account-name" placeholder="{{ __('admin.email') }}" value="{{ $data['email'] }}" data-validation-email-message="{{ __('admin.verify_the_email_format') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.phone') }}</label>
                                                            <input type="number" class="form-control" name="phone" id="account-name" placeholder="{{ __('admin.phone') }}" value="{{ $data['phone'] }}" minlength="10" required data-validation-required-message="{{ __('admin.this_field_is_required') }}" data-validation-minlength-message="{{ __('admin.the_number_should_only_be_less_than_ten_numbers') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                @php
                                                    $setting_data = (object) [
                                                        'address'         => $data['address'],
                                                        'map_desc'     => $data['map_desc'],
                                                        'lat'             => $data['lat'],
                                                        'lng'             => $data['lng'],
                                                    ];
                                                @endphp


                                            </div>

                                            <x-admin.input name="address" attr="required" :model="$setting_data"/>


                                            <div class="row">
                                                <x-admin.input name="map_desc" attr="required" :model="$setting_data"/>
                                                <x-admin.input name="lat" attr="required type='number' step='.01'" :model="$setting_data"/>
                                                <x-admin.input name="lng" attr="required type='number' step='.01'" :model="$setting_data"/>
                                            </div>

                                            <div class="row">

                                                <div class="col-12">
                                                    <div class="row">

                                                        <div class="imgMontg col-12 col-lg-4 col-md-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*" name="logo" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['logo'] }}">
                                                                            <button class="close">
                                                                                <i class="feather icon-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.logo_image') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="imgMontg col-12 col-lg-4 col-md-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*" name="fav_icon" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['fav_icon'] }}">
                                                                            <button class="close">
                                                                                <i class="feather icon-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.fav_icon_image') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="imgMontg col-12 col-lg-4 col-md-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*" name="login_background" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['login_background'] }}">
                                                                            <button class="close">
                                                                                <i class="feather icon-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.background_image') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="imgMontg col-12 col-lg-4 col-md-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*" name="default_user" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['default_user'] }}">
                                                                            <button class="close">
                                                                                <i class="feather icon-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.default_user_image') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="imgMontg col-12 col-lg-4 col-md-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*" name="default_data_image" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ asset($data['default_data_image']) }}">
                                                                            <button class="close">
                                                                                <i class="feather icon-trash-2"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.default_data_image') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-center mt-3">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                    <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                                </div>

                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="account-vertical-page" aria-labelledby="account-pill-page" aria-expanded="false">
                                        <form id="pages-form" action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="store">
                                            @method('put')
                                            @csrf

                                            <input type="hidden" name="type" value="order_wallet_data">

                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="tax_percentage">{{ __('admin.tax_percentage') }}</label>
                                                            <input type="text" class="form-control" name="tax" id="tax_percentage" placeholder="{{ __('admin.tax_percentage') }}" value="{{ $data['tax'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="commission">{{ __('admin.commission') }}</label>
                                                            <input type="text" class="form-control" name="commission" id="commission" placeholder="{{ __('admin.commission') }}" value="{{ $data['commission'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="order_duration_expiration">{{ __('admin.order_duration_expiration') }}</label>
                                                            <input type="text" class="form-control" name="order_duration_expiration" id="order_duration_expiration" placeholder="{{ __('admin.order_duration_expiration') }}" value="{{ $data['order_duration_expiration'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="rating_points">{{ __('admin.rating_points') }}</label>
                                                            <input type="text" class="form-control" name="rating_points" id="rating_points" placeholder="{{ __('admin.rating_points') }}" value="{{ $data['rating_points'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="points_per_riyal">{{ __('admin.points_per_riyal') }}</label>
                                                            <input type="text" class="form-control" name="points_per_riyal" id="points_per_riyal" placeholder="{{ __('admin.points_per_riyal') }}" value="{{ $data['points_per_riyal'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="points_validity_months">{{ __('admin.points_validity_months') }}</label>
                                                            <input type="text" class="form-control" name="points_validity_months" id="points_validity_months" placeholder="{{ __('admin.points_validity_months') }}" value="{{ $data['points_validity_months'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-center mt-3">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                            </div>

                                        </form>
                                    </div>


                                    <div class="tab-pane" id="account-vertical-api" aria-labelledby="account-pill-api" aria-expanded="false">
                                        <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="store">
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="type" value="api_data">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.google_analytics') }}</label>
                                                            <input type="text" class="form-control" name="google_analytics" id="account-name" placeholder="{{ __('admin.google_analytics') }}" value="{{ $data['google_analytics'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.google_places') }}</label>
                                                            <input type="text" class="form-control" name="google_places" id="account-name" placeholder="{{ __('admin.google_places') }}" value="{{ $data['google_places'] }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-center mt-3">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                    <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- account setting page end -->
    </div>
@endsection

@section('js')
    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('terms_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('terms_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('privacy_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('privacy_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('about_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('about_en_editor', {
            versionCheck: false
        });
    </script>
    {{-- show selected image script --}}
    @include('admin.shared.addImage')
    {{-- show selected image script --}}
@endsection
