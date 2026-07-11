@props(['name', 'multi_lang' => false, 'model' => null, 'type' => 'text', 'attr' => ''])


@if ($multi_lang)
    <div class="row">
        @foreach (languages() as $lang)
            <div class="col-md-6 col-12">
                <div class="form-group">
                    <label for="first-name-column">
                        {{ $field_name = __("admin.{$lang}_attribute", ['attribute' => trans("admin.$name")]) }}
                    </label>
                    <div class="controls">
                        <input type="text" name="{{ $name . '[' . $lang . ']' }}" {!! $attr !!}
                            value="{{ $model?->getTranslation($name, $lang) }}" class="form-control"
                            placeholder="{{ $field_name }}">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="col">
        <div class="form-group">
            <label for="first-name-column">
                {{ $field_name = trans("admin.$name") }}
            </label>
            <div class="controls">
                <input type="{{ $type }}" name="{{ $name }}" value="{{ $model?->$name }}" class="form-control"
                    placeholder="{{ $field_name }}" {!! $attr !!}>
            </div>
        </div>
    </div>


@endif
