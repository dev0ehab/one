@extends('admin.layout.master')

@section('css')
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/index_page.css') }}">
@endsection

@section('content')
<div class="content-body">

    <div class="neo-index-toolbar mb-2">

        <x-admin.buttons deletebutton="{{ route('admin.complains.deleteAll') }}" />

        <x-admin.filter datefilter="true" order="true" :searchArray="[
                'complain_number' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.complain_number'),
                ],
                'title' => [
                    'input_type' => 'text',
                    'input_name' => __('admin.title'),
                ],
                'status' => [
                    'input_type' => 'select',
                    'rows' => [
                        'new' => [
                            'name' => __('admin.complain_status.new'),
                            'id' => App\Enums\ComplainStatusEnum::NEW,
                        ],
                        'processing' => [
                            'name' => __('admin.complain_status.processing'),
                            'id' => App\Enums\ComplainStatusEnum::PENDING,
                        ],
                        'completed' => [
                            'name' => __('admin.complain_status.completed'),
                            'id' => App\Enums\ComplainStatusEnum::FINISH,
                        ],
                    ],
                    'input_name' => __('admin.status'),
                ],
            ]" />

    </div>

    <div class="table_content_append">

    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
@include('admin.shared.deleteAll')
@include('admin.shared.deleteOne')
@include('admin.shared.filter_js', ['index_route' => url('admin/complains/' . request()->client_id)])

<script>
$(document).on('click', '.change_status', function (e) {
    e.preventDefault()
    const url = $(this).data('url');
    const status = $(this).data('status');
    const needsReply = status === 'completed';
    
    if (needsReply) {
        // Show modal for completed status (requires reply)
        $('#reply_modal').modal('show');
        $('#reply_form').attr('action', url);
        // Clear previous reply
        $('#reply').val('');
    } else {
        // Direct status change for processing (no reply needed)
        Swal.fire({
            title: "{{ __('admin.are_you_want_to_continue') }}",
            text: "{{ __('admin.are_you_want_to_change_the_complain_status') }}",
            type: 'warning',
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
                $.ajax({
                    type: "post",
                    url: url,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: (response) => {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.complain_status_has_been_successfully_updated') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        getData({
                            'searchArray': searchArray()
                        })
                    },
                    error: function (response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'warning',
                            title: response.responseJSON.msg || response.responseJSON.message,
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                    }
                });
            }
        })
    }
});

// Handle reply form submission
$(document).on('submit', '#reply_form', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');
    var formData = new FormData($(this)[0]);
    
    $.ajax({
        url: url,
        method: 'post',
        data: formData,
        dataType: 'json',
        processData: false,
        contentType: false,
        beforeSend: function () {
            $(".send-reply-button").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
            ).attr('disabled', true)
        },
        success: function (response) {
            $(".text-danger").remove()
            $('.reply-form input, .reply-form textarea').removeClass('border-danger')
            $(".send-reply-button").html("{{ __('admin.send') }}").attr('disabled', false)
            $('#reply_modal').modal('hide');
            Swal.fire({
                position: 'top-start',
                type: 'success',
                title: '{{ __('admin.complain_status_has_been_successfully_updated') }}',
                showConfirmButton: false,
                timer: 1500,
                confirmButtonClass: 'btn btn-primary',
                buttonsStyling: false,
            })
            getData({
                'searchArray': searchArray()
            })
        },
        error: function (xhr) {
            $(".send-reply-button").html("{{ __('admin.send') }}").attr('disabled', false)
            $(".text-danger").remove()
            $('.reply-form input, .reply-form textarea').removeClass('border-danger')

            $.each(xhr.responseJSON.errors || {}, function (key, value) {
                $('.reply-form textarea[name=' + key + ']').addClass('border-danger')
                $('.reply-form textarea[name=' + key + ']').after(`<span class="mt-5 text-danger">${value}</span>`);
            });
            
            if (xhr.responseJSON.msg) {
                Swal.fire({
                    position: 'top-start',
                    type: 'warning',
                    title: xhr.responseJSON.msg,
                    showConfirmButton: false,
                    timer: 1500,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                })
            }
        },
    });
});
</script>

<!-- Reply Modal -->
<div class="modal fade text-left" id="reply_modal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary white">
                <h5 class="modal-title" id="replyModalLabel">{{ __('admin.the_reply') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reply_form" class="reply-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 col-12">
                        <div class="form-group">
                            <label for="reply">{{ __('admin.the_reply') }}</label>
                            <div class="controls">
                                <textarea name="reply" id="reply" class="form-control" cols="30" rows="10" required
                                    data-parsley-required-message="{{__('admin.this_field_is_required')}}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary send-reply-button">
                            {{ __('admin.send') }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('admin.cancel') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
