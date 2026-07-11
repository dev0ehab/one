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
                {{-- <div class="col-md-3 mb-2 mb-md-0">
                    <ul class="nav nav-pills flex-column mt-md-0 mt-1">

                        <li class="nav-item">
                            <a class="nav-link d-flex py-75 active" id="account-pill-main" data-toggle="pill" href="#account-vertical-main" aria-expanded="true">
                                <i class="feather icon-settings mr-50 font-medium-3"></i>
                                {{ __('routes.intro_sites') }}
                            </a>
                        </li>

                    </ul>
                </div> --}}
                <!-- right content section -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">

                                    @php
                                        $pages = [
                                            'landing_section',
                                            'services_section',
                                            'sections_section',
                                            'stats_section',
                                            'video_section',
                                            'newsletter_section',
                                            'contact_us_section',
                                            'app_section',
                                        ];

                                    @endphp

                                    <div class="tab-pane active" id="account-vertical-main" aria-labelledby="account-pill-main" aria-expanded="true">
                                        <form id="pages-form" action="{{ route('admin.intro_sites.update') }}" method="post" enctype="multipart/form-data" class="store">
                                            @method('put')
                                            @csrf

                                            <input type="hidden" name="type" value="intro_sites_data">

                                            <ul class="nav nav-tabs" id="myTab" role="tablist">

                                                <li class="nav-item">
                                                    <a class="nav-link active" id="landing_section-tab" data-toggle="tab" href="#landing_section" role="tab" aria-controls="landing_section" aria-selected="true">{{ __('routes.landing_section') }}</a>
                                                </li>

                                                @foreach ($pages as $page)
                                                    @if ($page == 'landing_section')
                                                        @continue
                                                    @endif
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="{{ $page }}-tab" data-toggle="tab" href="#{{ $page }}" role="tab" aria-controls="{{ $page }}" aria-selected="true">{{ __("routes.{$page}") }}</a>
                                                    </li>
                                                @endforeach

                                            </ul>

                                            <div class="tab-content" id="myTabContent">

                                                @include('admin.intro_sites.parts.landing_section')
                                                @include('admin.intro_sites.parts.services_section')
                                                @include('admin.intro_sites.parts.sections_section')
                                                @include('admin.intro_sites.parts.stats_section')
                                                @include('admin.intro_sites.parts.video_section')
                                                @include('admin.intro_sites.parts.newsletter_section')
                                                @include('admin.intro_sites.parts.contact_us_section')
                                                @include('admin.intro_sites.parts.app_section')

                                            </div>

                                            <div class="col-12 d-flex justify-content-center mt-3">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">{{ __('admin.saving_changes') }}</button>
                                                <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
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