@extends('admin.layout.master')

{{-- extra css files --}}
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
@endsection
{{-- extra css files --}}

@section('content')
    <!-- // Basic multiple Column Form section start -->
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.edit') }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.services.update', ['id' => $service->id]) }}" class=" store form-horizontal parsley" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="form-body">

                                    <x-admin.image name="image" :model="$service"/>

                                    <x-admin.input name="title" :model="$service" :multi_lang="true" attr="required"/>

                                    <x-admin.textarea :has_editor="true" name="description" :model="$service" :multi_lang="true" attr="required"/>

                                    <x-admin.switch name="is_active" :model="$service" :label="true" :ajax="false"/>


                                    <h2 class="py-2"> {{ __('admin.parts') }} </h2>

                                    <div id="form-repeater">
                                        <div data-repeater-list="parts">

                                            @foreach ($service->parts as $part)
                                                <div data-repeater-item>
                                                    <div class="row">
                                                        <div class="col-11">
                                                            <input type="hidden" class="part_id" name="id" value="{{ $part->id }}">
                                                            <x-admin.image name="image" :model="$part"/>
                                                            <x-admin.textarea :has_editor="true" :model="$part" name="description" :multi_lang="true" attr="required"/>
                                                        </div>

                                                        <div class="col-1" style="align-self: center;">
                                                            <button type="button" class="btn btn-sm btn-danger" data-repeater-delete>
                                                                {{ __('admin.delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary add" data-repeater-create>
                                            <h4 class="m-0">+</h4>
                                        </button>
                                    </div>


                                    <h2 class="py-2"> {{ __('admin.importants') }} </h2>

                                    <div id="form-repeater-2">
                                        <div data-repeater-list="importants">

                                            @foreach ($service->importants as $important)
                                                <div data-repeater-item>
                                                    <div class="row">
                                                        <div class="col-11">
                                                            <input type="hidden" class="important_id" name="id" value="{{ $important->id }}">

                                                            <x-admin.input name="title" :multi_lang="true" :model="$important" attr="required"/>
                                                        </div>
                                                        <div class="col-1" style="align-self: center;">
                                                            <button type="button" class="btn btn-sm btn-danger" data-repeater-delete>
                                                                {{ __('admin.delete') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach


                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary add" data-repeater-create>
                                            <h4 class="m-0">+</h4>
                                        </button>
                                    </div>


                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-center mt-3">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1 submit_button">
                                                {{ __('admin.update') }}
                                            </button>
                                            <a href="{{ url()->previous() }}" type="reset" class="btn btn-outline-warning mr-1 mb-1">
                                                {{ __('admin.back') }}
                                            </a>
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
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>

    {{-- show selected image script --}}
    @include('admin.shared.addImage')
    {{-- show selected image script --}}

    {{-- submit edit form script --}}
    @include('admin.shared.submitEditForm')
    {{-- submit edit form script --}}
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>
@push('js')
    <!-- In your app.blade.php or directly in the form.blade.php -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#form-repeater-2').repeater({
                initEmpty: false, // Set to false if you want one default item
                show: function () {
                    $(this).slideDown();
                    $(".important_id").last().val("");

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                isFirstItemUndeletable: true
            });

            $('#form-repeater').repeater({
                initEmpty: false, // Set to false if you want one default item
                show: function () {
                    $(this).slideDown();

                    $(".uploadedBlock").last().remove();
                    $(".part_id").last().val("");

                    // Re-initialize CKEditor for new items

                    $(this).find('textarea.editor').each(function (x, y) {
                        CKEDITOR.checkVersion = false;

                        lang = $(y).data("lang");
                        dir = lang == 'ar' ? 'rtl' : 'ltr';

                        CKEDITOR.replace(this, {
                            extraPlugins: 'colorbutton,colordialog,font',
                            filebrowserUploadMethod: 'form',
                            contentsLangDirection: dir,
                            language: lang,
                            removePlugins: 'image,flash,iframe,smiley,about',
                            fontSize_sizes: '8/8px;10/10px;12/12px;14/14px;16/16px;18/18px;24/24px;36/36px;',
                            contentsCss: [
                                'https://fonts.googleapis.com/css2?family=Amiri&family=Cairo&display=swap',
                                CKEDITOR.basePath + 'contents.css'
                            ],
                            font_names: 'Amiri/Amiri;' +
                                'Cairo/Cairo, sans-serif;' +
                                'Droid Arabic Kufi/Droid Arabic Kufi;' +
                                'Traditional Arabic/Traditional Arabic;' +
                                'Scheherazade/Scheherazade;' +
                                'Arial/Arial, Helvetica, sans-serif;' +
                                'Times New Roman/Times New Roman, Times, serif;' +
                                'Courier New/Courier New, Courier, monospace;' +
                                'Georgia/Georgia, serif;' +
                                'Verdana/Verdana, Geneva, sans-serif;' +
                                'Tahoma/Tahoma, Geneva, sans-serif'
                        });
                    });

                    // ADD IMAGE
                    $('.imageUploader').change(function (event) {
                        $(this).parents('.imagesUploadBlock').append(
                            '<div class="uploadedBlock"><img src="' + URL
                                .createObjectURL(
                                    event.target.files[0]) +
                            '"><button class="close"><i class="la la-times"></i></button></div>'
                        );
                    });

                    $('.dropBox').on('click', '.close', function () {
                        $(this).parent().remove();
                    });

                    $(".clickAdd").click(function (b) {
                        b.preventDefault();
                        $('.dropBox').append('<div class="textCenter">' +
                            '<div class="imagesUploadBlock">' +
                            '<label class="uploadImg">' +
                            '<span><i class="feather icon-image"></i></span>' +
                            '<input type="file" accept="image/*" class="imageUploader">' +
                            '</label>' + '</div>' + '</div>');

                        $('.imageUploader').change(function (event) {
                            $(this).parents('.imagesUploadBlock').append(
                                '<div class="uploadedBlock"><img src="' + URL
                                    .createObjectURL(event.target.files[0]) +
                                '"><button class="close"><i class="feather icon-x"></i></button></div>'
                            );
                        });
                        $('.dropBox').on('click', '.close', function () {
                            $(this).parents('.textCenter').remove();
                        });


                    });


                    setTimeout(function () {
                        $('.cke_notification').remove();
                    }, 400); // 2000ms = 2 seconds

                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                isFirstItemUndeletable: true
            });

            // // Initialize existing editors
            // $('textarea.editor').each(function() {
            //     CKEDITOR.replace(this);
            // });
        });
    </script>
@endpush
