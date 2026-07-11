@props([
    'name',
    'multi_lang' => false,
    'model' => null,
    'rows' => 10,
    'cols' => 30,
    'has_editor' => false,
    'attr' => '',
    'show' => false,
])

@php
    $class = $has_editor ? 'editor' : '';
    $calssVal = $name;
    $id = uniqid();
@endphp


<div class="row">

    @if ($multi_lang)

        @foreach (languages() as $lang)
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-column">
                        {{ $field_name = __("admin.{$lang}_attribute", ['attribute' => trans("admin.$name")]) }}
                    </label>

                    <textarea data-lang="{{$lang}}" placeholder="{{ $field_name }}" id="{{ $id . '-' . $lang }}" class="{{ $calssVal . ' form-control ' . $class }}" {!! $attr !!} name="{{ $name . '[' . $lang . ']' }}" cols="{{ $cols }}" rows="{{ $rows }}">{{ $model?->getTranslation($name, $lang) }}</textarea>

                </div>
            </div>
        @endforeach
    @else
        <div class="col">
            <div class="form-group">
                <label for="first-name-column">
                    {{ $field_name = trans("admin.$name") }}
                </label>
                <div class="controls">

                    <textarea placeholder="{{ $field_name }}" id="{{ $id }}" class="{{ $calssVal . 'form-control ' . $class }}" {!! $attr !!} name="{{ $name }}" cols="{{ $cols }}" rows="{{ $rows }}">{{ $model?->$name }}</textarea>

                </div>
            </div>
        </div>

    @endif

</div>



@push('js')
    <script src="https://cdn.ckeditor.com/4.16.2/full-all/ckeditor.js"></script>

    <script>
        CKEDITOR.replaceClass = 'editor';
        $('.editor').each(function (x, y) {

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

    </script>
@endpush

@if ($show)
    @push('js')
        <script>
            $("." + @json($calssVal)).attr('disabled', true).css('opacity', '1')
        </script>
    @endpush
@endif
