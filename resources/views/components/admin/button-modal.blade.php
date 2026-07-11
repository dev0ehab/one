@props(['additional_input_name' => null, 'label' => null, 'url' => '', 'key' => '', 'value' => '', 'icon' => 'feather icon-image', 'method' => 'post', 'btn' => 'success'])

<button class='{{"btn btn-$btn change-status"}}' style="padding: 5px" data-key="{{ $key }}" data-value="{{ $value }}"
    data-url="{{ $url }}" data-additional_input_name="{{ $additional_input_name }}" data-method="{{ $method }}"
    data-additional_input_place='{{ __("admin.please_enter", ["input" => __("admin.$additional_input_name")]) }}'>
    <i class="{{ $icon }}"></i>

    {{ $label ?? '' }}
</button>

<script>
    $(document).on('click', '.change-status', function (e) {
        e.preventDefault();

        var id = $(this).data('id');
        var key = $(this).data('key');
        var value = $(this).data('value');
        var route = $(this).data('url');
        var method = $(this).data('method');
        var input = $(this).data('additional_input_name') ? 'text' : false;
        var input_name = $(this).data('additional_input_name') ?? '';
        var additional_input_place = $(this).data('additional_input_place');



        Swal.fire({
            title: "{{__('admin.are_you_want_to_continue')}}",
            text: "{{__('admin.are_you_want_to_complete_the_this_process')}}",
            type: 'warning',
            input: input,
            inputPlaceholder: additional_input_place,
            inputValidator: (value) => {
                if (!value.trim()) {
                    return additional_input_place;
                }
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __('admin.confirm') }}',
            confirmButtonClass: 'btn btn-primary',
            cancelButtonText: '{{ __('admin.cancel') }}',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
        }).then((result) => {
            if (result.value) {
                input_value = result.value ?? '';

                Swal.fire({
                    title: '<i class="fa fa-spinner fa-spin"></i>',
                    text: '{{ __('admin.loading') }}',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                let data = {
                    _token: '{{ csrf_token() }}'
                }

                data[key] = value;
                data[input_name] = input_value;

                $.ajax({
                    url: route,
                    type: method,
                    data: data,
                    success: function (response) {
                        Swal.close();
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.update_successfully') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                        getData({
                            'searchArray': searchArray()
                        });
                    },
                    error: function (xhr) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('admin.error') }}',
                            text: xhr.responseJSON?.message ||
                                '{{ __('admin.error') }}',
                            confirmButtonClass: 'btn btn-danger',
                        });
                    }
                });
            }
        });
    });
</script>

<style>
    .change-status {
        min-width: 40px;
        min-height: 40px;
        display: inline-flex;
        border-radius: 15px;
        justify-content: center;
        align-items: center;
        font-size: 16px;
    }

    .btn i {
        float: right;
        margin-left: 0;
    }
</style>