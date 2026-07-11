@props(['model' => null, 'name' => 'is_active', 'url' => null, 'label' => false, 'ajax' => true , 'attr' => ''])


@if ($label)
    <label class="form-label">{{ trans("admin.$name") }}</label>
@endif

<input type="hidden" name="{{ $name }}" value="0">
<label class="switch">
    <input type="checkbox" {!! $attr !!} name="{{ $name }}" value="{{ $model?->$name ?? '1' }}" data-model="{{ $model?->id }}" id="{{ $name }}-{{ $model?->id }}"
        {{ ($name == 'is_active' && $model?->isActive()) || ($name != 'is_active' && $model?->$name) ? 'checked' : '' }}>
    <span class="slider round"></span>
</label>


<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 30px;
        height: 17px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 13px;
        width: 13px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #45cb85;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #45cb85;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(13px);
        -ms-transform: translateX(13px);
        transform: translateX(13px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 17px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>


<script>
    $(document).ready(function() {
        $("#{{ $name }}-{{ $model?->id }}").on("change", function() {
            var model = $(this).data("model");
            var status = $(this).is(':checked') ? 1 : 0;

            if ("{{ $ajax }}") {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "{{ $url }}" + model,
                    data: {
                        "{{ $name }}": status,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.the_selected_has_been_successfully_changed_activation') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'error',
                            title: '{{ __('admin.error_occurred') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        // Revert the checkbox state
                        $("#{{ $name }}-{{ $model?->id }}").prop('checked', !status);
                    }
                });
            }
        });
    });
</script>
