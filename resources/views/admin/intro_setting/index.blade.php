@extends('admin.layout.master')

@section('content')
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">
                <!-- left menu section -->
                <div class="col-md-3 mb-2 mb-md-0">
                    <ul class="nav nav-pills flex-column mt-md-0 mt-1">

                        <li class="nav-item">
                            <a class="nav-link d-flex py-75 active" id="account-pill-main" data-toggle="pill"
                                href="#account-vertical-main" aria-expanded="true">
                                <i class="feather icon-globe mr-50 font-medium-3"></i>
                                {{ __('admin.app_setting') }}
                            </a>
                        </li>
                        <li class="nav-item" style="margin-top: 3px">
                            <a class="nav-link d-flex py-75" id="account-pill-texts" data-toggle="pill"
                                href="#account-vertical-texts" aria-expanded="false">
                                <i class="feather icon-edit mr-50 font-medium-3"></i>
                                {{ __('admin.frequent_texts') }}
                            </a>
                        </li>

                        <li class="nav-item" style="margin-top: 3px">
                            <a class="nav-link d-flex py-75" id="account-pill-about" data-toggle="pill"
                                href="#account-vertical-about" aria-expanded="false">
                                <i class="feather icon-file mr-50 font-medium-3"></i>
                                {{ __('admin.about_us') }}
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
                                    <div class="tab-pane active" id="account-vertical-main"
                                        aria-labelledby="account-pill-main" aria-expanded="true">
                                        <form action="{{ route('admin.intro_settings.update') }}" method="post"
                                            enctype="multipart/form-data" class="form-horizontal store parsley" novalidate>
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="type" value="intro_app_setting">
                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.name_of_induction_in_arabic') }}</label>
                                                            <input type="text" class="form-control" name="intro_name_ar"
                                                                id="account-name"
                                                                placeholder="{{ __('admin.name_of_induction_in_arabic') }}"
                                                                value="{{ $data['intro_name_ar'] }}" required
                                                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.name_of_the_induction_of_english') }}</label>
                                                            <input type="text" class="form-control" name="intro_name_en"
                                                                id="account-name"
                                                                placeholder="{{ __('admin.name_of_the_induction_of_english') }}"
                                                                value="{{ $data['intro_name_en'] }}" required
                                                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.email') }}</label>
                                                            <input type="email" class="form-control" name="intro_email"
                                                                id="account-name" placeholder="{{ __('admin.email') }}"
                                                                value="{{ $data['intro_email'] }}" required
                                                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}"
                                                                data-parsley-email-message="{{ __('admin.email_formula_is_incorrect') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.phone') }}</label>
                                                            <input type="text" class="form-control" name="intro_phone"
                                                                minlength="10" id="account-name"
                                                                placeholder="{{ __('admin.phone') }}"
                                                                value="{{ $data['intro_phone'] }}" required
                                                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}"
                                                                data-parsley-minlength-message="{{ __('admin.the_number_should_only_be_less_than_ten_numbers') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">{{ __('admin.address') }}</label>
                                                            <input type="text" class="form-control" name="intro_address"
                                                                id="account-name" placeholder="{{ __('admin.address') }}"
                                                                value="{{ $data['intro_address'] }}" required
                                                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label
                                                                        for="account-name">{{ __('admin.The_main_website_color') }}</label>
                                                                    <input type="color" class="form-control"
                                                                        name="color" id="account-name"
                                                                        placeholder="{{ __('admin.The_main_website_color') }}"
                                                                        value="{{ $data['color'] }}" required
                                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label
                                                                        for="account-name">{{ __('admin.the_color_of_the_buttons') }}</label>
                                                                    <input type="color" class="form-control"
                                                                        name="buttons_color" id="account-name"
                                                                        placeholder="{{ __('admin.the_color_of_the_buttons') }}"
                                                                        value="{{ $data['buttons_color'] }}" required
                                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-4">
                                                            <div class="form-group">
                                                                <div class="controls">
                                                                    <label
                                                                        for="account-name">{{ __('admin.color_of_hover') }}</label>
                                                                    <input type="color" class="form-control"
                                                                        name="hover_color" id="account-name"
                                                                        placeholder="{{ __('admin.color_of_hover') }}"
                                                                        value="{{ $data['hover_color'] }}" required
                                                                        data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row justify-content-center">

                                                        <div class="imgMontg col-lg-4 col-md-12 text-center">
                                                            <div class="dropBox">
                                                                <div
                                                                    class="textCenter d-flex flex-lg-column flex-wrap flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*"
                                                                                name="intro_logo" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['intro_logo'] }}">
                                                                            <button class="close"><i
                                                                                    class="feather icon-trash-2"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.logo_image_induction') }}</span>
                                                                    <div id="intro_logo_error" class="text-danger mt-1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="imgMontg col-lg-4 col-md-12 text-center">
                                                            <div class="dropBox">
                                                                <div
                                                                    class="textCenter d-flex flex-lg-column flex-wrap flex-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*"
                                                                                name="intro_loader" class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['intro_loader'] }}">
                                                                            <button class="close"><i
                                                                                    class="feather icon-trash-2"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.picture_of_loader') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="col-12 d-flex justify-content-center mt-3">
                                                    <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                    <a href="{{ url()->previous() }}" type="reset"
                                                        class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane" id="account-vertical-texts"
                                        aria-labelledby="account-pill-texts" aria-expanded="false">
                                        <form action="{{ route('admin.intro_settings.update') }}" method="post"
                                            enctype="multipart/form-data" class="form-horizontal store parsley">
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="type" value="frequent_texts">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.address_of_our_services_section_in_arabic') }}</label>
                                                            <textarea class="form-control" name="services_text_ar" id="services_text_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.address_of_our_services_section_in_arabic') }}">{{ $data['services_text_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.address_of_our_services_section_in_english') }}</label>
                                                            <textarea class="form-control" name="services_text_en" id="services_text_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.address_of_our_services_section_in_english') }}">{{ $data['services_text_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_title_of_how_the_site_works_in_arabic') }}</label>
                                                            <textarea class="form-control" name="how_work_text_ar" id="how_work_text_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_title_of_how_the_site_works_in_arabic') }}">{{ $data['how_work_text_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_title_of_how_the_site_works_in_english') }}</label>
                                                            <textarea class="form-control" name="how_work_text_en" id="how_work_text_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_title_of_how_the_site_works_in_english') }}">{{ $data['how_work_text_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_address_of_the_questions_section_in_arabic') }}</label>
                                                            <textarea class="form-control" name="fqs_text_ar" id="fqs_text_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_address_of_the_questions_section_in_arabic') }}">{{ $data['fqs_text_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_address_of_the_questions_section_english') }}</label>
                                                            <textarea class="form-control" name="fqs_text_en" id="fqs_text_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_address_of_the_questions_section_english') }}">{{ $data['fqs_text_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_title_of_our_partition_in_arabic') }}</label>
                                                            <textarea class="form-control" name="partners_text_ar" id="partners_text_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_title_of_our_partition_in_arabic') }}">{{ $data['partners_text_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.the_title_of_our_partition_in_english') }}</label>
                                                            <textarea class="form-control" name="partners_text_en" id="partners_text_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.the_title_of_our_partition_in_english') }}">{{ $data['partners_text_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.address_in_arabic_communication') }}</label>
                                                            <textarea class="form-control" name="contact_text_ar" id="contact_text_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.address_in_arabic_communication') }}">{{ $data['contact_text_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.address_in_english_communication') }}</label>
                                                            <textarea class="form-control" name="contact_text_en" id="contact_text_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.address_in_english_communication') }}">{{ $data['contact_text_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-center mt-3">
                                                    <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                    <a href="{{ url()->previous() }}" type="reset"
                                                        class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <div role="tabpanel" class="tab-pane" id="account-vertical-about"
                                        aria-labelledby="account-pill-about" aria-expanded="false">
                                        <form action="{{ route('admin.intro_settings.update') }}" method="post"
                                            enctype="multipart/form-data">
                                            @method('put')
                                            @csrf
                                            <input type="hidden" name="type" value="intro_about_app">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="imgMontg col-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-lg-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*"
                                                                                name="about_image_1"
                                                                                class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['about_image_1'] }}">
                                                                            <button class="close"><i
                                                                                    class="feather icon-trash-2"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.image_of_the_first_application') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="imgMontg col-6 text-center">
                                                            <div class="dropBox">
                                                                <div class="textCenter d-flex flex-lg-column">
                                                                    <div class="imagesUploadBlock">
                                                                        <label class="uploadImg">
                                                                            <span><i class="feather icon-image"></i></span>
                                                                            <input type="file" accept="image/*"
                                                                                name="about_image_2"
                                                                                class="imageUploader">
                                                                        </label>
                                                                        <div class="uploadedBlock">
                                                                            <img src="{{ $data['about_image_2'] }}">
                                                                            <button class="close"><i
                                                                                    class="feather icon-trash-2"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <span>{{ __('admin.image_of_the_second_application') }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.about_the_arabic_application') }}</label>
                                                            <textarea class="form-control" name="intro_about_ar" id="intro_about_ar_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.about_the_arabic_application') }}">{{ $data['intro_about_ar'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label
                                                                for="account-name">{{ __('admin.about_the_english_application') }}</label>
                                                            <textarea class="form-control" name="intro_about_en" id="intro_about_en_editor" cols="30" rows="10"
                                                                placeholder="{{ __('admin.about_the_english_application') }}">{{ $data['intro_about_en'] }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12 d-flex justify-content-center mt-3">
                                                    <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                    <a href="{{ url()->previous() }}" type="reset"
                                                        class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
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
        CKEDITOR.replace('services_text_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('services_text_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('how_work_text_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('how_work_text_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('fqs_text_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('fqs_text_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('partners_text_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('partners_text_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('contact_text_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('contact_text_en_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('intro_about_ar_editor', {
            versionCheck: false
        });
        CKEDITOR.replace('intro_about_en_editor', {
            versionCheck: false
        });
    </script>
    {{-- show selected image script --}}
    @include('admin.shared.addImage')
    {{-- show selected image script --}}

@endsection
