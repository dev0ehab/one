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
    $editorClass = $has_editor ? 'ckeck-editor' : '';
    $id = uniqid();
@endphp

<div class="row">
    @if ($multi_lang)
        @foreach (languages() as $lang)
            <div class="col-6">
                <div class="form-group">
                    <label>
                        {{ __("admin.{$lang}_attribute", ['attribute' => trans("admin.$name")]) }}
                    </label>
                    <textarea data-lang="{{ $lang }}" id="{{ $id .'-'. $lang }}"
                        placeholder="{{ __("admin.{$lang}_attribute", ['attribute' => trans("admin.$name")]) }}"
                        class="form-control  {{ $editorClass }}" name="{{ $name . '[' . $lang . ']' }}" cols="{{ $cols }}"
                        rows="{{ $rows }}" {!! $attr !!}>{{ $model?->getTranslation($name, $lang) }}</textarea>
                </div>
            </div>
        @endforeach
    @else
        <div class="col">
            <div class="form-group">
                <label>
                    {{ trans("admin.$name") }}
                </label>
                <textarea data-lang="{{ lang() }}" id="{{ $id }}" placeholder="{{ trans("admin.$name") }}"
                    class="form-control {{ $editorClass }}" name="{{ $name }}" cols="{{ $cols }}"
                    rows="{{ $rows }}" {!! $attr !!}>{{ $model?->$name }}</textarea>
            </div>
        </div>
    @endif
</div>
@push('js')
    <!-- CKEditor 5 Classic build -->
    <script src="{{ asset('js/check5.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editors = document.querySelectorAll('textarea');
            editors.forEach((textarea) => {
                const lang = $(textarea).data("lang");

                CKEDITOR.ClassicEditor.create(textarea, {
                    language: lang,
                    removePlugins: [
                        'RealTimeCollaborativeComments',
                        'RealTimeCollaborativeTrackChanges',
                        'RealTimeCollaborativeRevisionHistory',
                        'PresenceList',
                        'PasteFromOfficeEnhanced',
                        'Comments',
                        'TrackChanges',
                        'TrackChangesData',
                        'RevisionHistory',
                        'Pagination',
                        'WProofreader',
                        'MathType',
                        'SlashCommand',
                        'Template',
                        'DocumentOutline',
                        'FormatPainter',
                        'TableOfContents'
                    ],
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'fontColor', 'fontBackgroundColor', '|',
                            'alignment', 'numberedList', 'bulletedList', '|',
                            'link', 'insertTable', 'imageUpload', '|',
                            'undo', 'redo'
                        ]
                    },
                }).catch(error => {
                    console.error(error);
                });
            });
        });
    </script>
@endpush



@if ($show)
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.ckeck-editor').forEach(el => {
                    el.setAttribute('disabled', true);
                    el.style.opacity = '1';
                });
            });
        </script>
    @endpush
@endif

@push('css')
    <style>
        .ck-blurred,
        .ck-focused {
            height: 200px;
            background: rgb(235, 232, 232);
            white-space: pre-wrap;

        }

        .ck-blurred p,
        .ck-focused p {
            color: black !important
        }

        body {
            overflow-x: hidden;
        }
    </style>
@endpush
