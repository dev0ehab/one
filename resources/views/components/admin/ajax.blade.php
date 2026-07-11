@php
    $multipleClass = $multiple ? $selector . '-multiple' : '';
    $name = $multiple ? $selector . '_id[]' : $selector . '_id';
    $options = old("{$selector}_options")
        ? collect(json_decode(old("{$selector}_options"), true))
            ->pluck('text', 'id')
            ->toArray()
        : $options;
    $chosenOptionIds = old($selector . '_id')
        ? ($multiple
            ? old($selector . '_id')
            : [old($selector . '_id')])
        : $chosenOptionIds;
@endphp

<input type="hidden" id="{{ $selector . '_options' }}" name="{{ $selector . '_options' }}"
    value="{{ json_encode($options) }}">

<div class="{{ 'main-select form-group col-4 ' . $selector . '_div ' . $class }}" data_select="{{ $selector }}"
    data_step = '{{ $step }}'>
    <label for="{{ $selector }}">
        @if (isset($trans))
            @lang($trans)
        @else
            @lang($selector . 's::' . $selector . 's.singular')
        @endif
    </label>

    <select @isset($required) @if ($required) required @endif @endisset
        class="{{ 'form-control ' . $multipleClass }}" @if ($multiple) multiple @endif
        name="{{ $name }}" id="{{ $selector . '_id' }}" data_step = '{{ $step }}'>

        @foreach ($options as $id => $option)
            <option value="{{ $id }}" @if (in_array($id, $chosenOptionIds)) selected @endif>
                {{ $option }}
            </option>
        @endforeach
    </select>
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $("{{ '.' . $selector . '-multiple' }}").select2();
        });

        $("{{ '#' . $action . '_id' }}").on("change", function(x) {
            var modelId = $(this).val();
            var url = window.location.origin;

            dataStep = +$(x.target).attr("data_step");

            if (Number.isInteger(+modelId) && modelId != "") {
                // session = `{{ json_encode(session('selector')) }}`,
                session = "{{ json_encode(data_get(session()->all(), 'selector', [])) }}",
                    $.ajax({
                        type: "POST",
                        url: url + "/{{ $routeUrl }}/" + modelId,
                        data: {
                            "session": session
                        },
                        headers: {
                            "ACCEPT-LANGUAGE": "{{ app()->getLocale() }}"
                        },
                        success: function(response) {
                            $("{{ '.' . $selector . '_div' }}").removeClass("d-none");

                            if ($("{{ '.' . $selector . '-multiple' }}").length == 0) {
                                $("{{ '#' . $selector . '_id' }}").empty()
                                    .append("<option> {{ __('Select one') }} </option>");
                            } else {
                                $("{{ '#' . $selector . '_id' }}").empty();
                            }

                            response.data.forEach(newModel => {
                                $("{{ '#' . $selector . '_id' }}").append(
                                    `<option value="${newModel.id}">${newModel.text}</option>`
                                );
                            });

                            $("{{ '#' . $selector . '_options' }}").val(JSON.stringify(response.data));
                        }
                    });

                removeSelector(dataStep + 1);
            } else {
                removeSelector(dataStep);
            }
        });


        function removeSelector(current) {
            $('.main-select').each(function(i, el) {
                if ($(el).attr("data_step") > current) {
                    $(el).addClass("d-none")
                }
            })
        }
    </script>
@endpush
