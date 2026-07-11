@extends('admin.layout.master')

{{-- extra css files --}}
@section('css')
<link rel="stylesheet" type="text/css"
    href="{{asset('admin/app-assets/css-rtl/plugins/forms/validation/form-validation.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css')}}">
@endsection
{{-- extra css files --}}

@section('content')
<!-- // Basic multiple Column Form section start -->
<section id="multiple-column-form">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{__('admin.update') . ' ' . __('admin.pages')}}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form method="POST" action="{{route('admin.pages.update', ['id' => $page->id])}}"
                            class=" store form-horizontal parsley" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-body">

                                <x-admin.image name="image" :model="$page" />

                                <div class="row">
                                    @foreach (languages() as $lang)
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">{{__('site.title_' . $lang)}}</label>
                                                <div class="controls">
                                                    <input type="text" value="{{$page->getTranslations('title')[$lang]}}"
                                                        name="title[{{$lang}}]" class="form-control"
                                                        placeholder="{{__('site.write') . ' ' . __('site.title_' . $lang)}}"
                                                        required
                                                        data-validation-required-message="{{__('admin.this_field_is_required')}}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>


                                <x-admin.textarea :model="$page" name="content" multi_lang="true" :has_editor="true" />


                                <div class="row">

                                    <div class="col-12 d-flex justify-content-center mt-3">
                                        <button type="submit"
                                            class="btn btn-primary mr-1 mb-1 submit_button">{{__('admin.update')}}</button>
                                        <a href="{{ url()->previous() }}" type="reset"
                                            class="btn btn-outline-warning mr-1 mb-1">{{__('admin.back')}}</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script src="{{asset('admin/app-assets/vendors/js/forms/validation/jqBootstrapValidation.js')}}"></script>
<script src="{{asset('admin/app-assets/js/scripts/forms/validation/form-validation.js')}}"></script>
<script src="{{asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js')}}"></script>
<script src="{{asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js')}}"></script>

{{-- show selected image script --}}
@include('admin.shared.addImage')
{{-- show selected image script --}}

{{-- submit edit form script --}}
@include('admin.shared.submitEditForm')
{{-- submit edit form script --}}

@endsection