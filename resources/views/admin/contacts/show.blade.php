@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/lightbox/css/lightbox.min.css') }}">
@endsection

@section('content')
    <section id="multiple-column-form">
        <div class="contact match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ __('admin.the_resolution_of_contact') }}</h4>
                    </div>

                    <div class="card-content">
                        <div class="card-body">
                            <form>
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">

                                        <div class="col-md-4 col-4">
                                            <div class="form-group">
                                                <label for="first-name-column">{{ __('admin.f_name') }}</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control"
                                                        value="{{ $contact->contactable?->name ?? $contact->name }}" disabled>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-4 col-6">
                                            <div class="form-group">
                                                <label for="type-column">{{ __('admin.type') }}</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control"
                                                        value="{{ __('admin.' . ($contact->type ?? 'complaint')) }}" disabled>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-4 col-6">
                                            <div class="form-group">
                                                <label for="first-name-column">{{ __('admin.phone') }}</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control" value="{{ $contact->contactable?->phone ?? $contact->phone }}"
                                                        disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-6">
                                            <div class="form-group">
                                                <label for="first-name-column">{{ __('admin.email') }}</label>
                                                <div class="controls">
                                                    <input type="text" class="form-control"
                                                        value="{{ $contact->contactable?->email ?? '-' }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 col-12">
                                            <div class="form-group">
                                                <label for="first-name-column">{{ __('admin.contact') }}</label>
                                                <div class="controls">
                                                    <textarea class="form-control" cols="30" contacts="10" disabled>{{ $contact->message }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($contact->images && $contact->images->count() > 0)
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="images-column">{{ __('admin.images') }}</label>
                                                    <div class="controls">
                                                        <div class="row">
                                                            @foreach($contact->images as $image)
                                                                <div class="col-md-3 col-sm-4 col-6 mb-3">
                                                                    <a href="{{ $image->image }}" data-lightbox="contact-images" data-title="{{ __('admin.contact') }} {{ __('admin.image') }}">
                                                                        <img src="{{ $image->image }}" alt="{{ __('admin.contact') }} {{ __('admin.image') }}" 
                                                                             class="img-thumbnail" 
                                                                             style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;">
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($contact->reply)
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    <label for="first-name-column">{{ __('admin.reply') }}</label>
                                                    <div class="controls">
                                                        <textarea class="form-control" cols="30" rows="10" disabled>{{ $contact->reply }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif


                                        <div class="col-12 d-flex justify-content-center mt-3">
                                            <a href="{{ url()->previous() }}" type="reset"
                                                class="btn btn-outline-warning mr-1 mb-1">{{ __('admin.back') }}</a>
                                            @if (!$contact->reply && $contact->contactable_type)
                                                <a data-toggle="modal" id="anchor" data-target="#reply"
                                                    class="btn btn-outline-primary mr-1 mb-1">
                                                    {{ __('admin.reply') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($contact->contactable_type)
        <div class="modal fade text-left" id="reply" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <h5 class="modal-title" id="myModalLabel160">{{ __('admin.the_reply') }}</h5>
                    <div class="modal-header bg-primary white">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.contacts.reply', ['id' => $contact->id]) }}" method="POST"
                            enctype="multipart/form-data" class="notify-form store parsley">
                            @csrf
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">{{ __('admin.the_reply') }}</label>
                                    <div class="controls">
                                        <textarea name="reply" class="form-control" cols="30" rows="10" required
                                            data-validation-required-message="{{ __('admin.this_field_is_required') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary send-notify-button">
                                    {{ __('admin.send') }}
                                </button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">
                                    {{ __('admin.cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
@endsection

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('submit', '.notify-form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action')
                $.ajax({
                    url: url,
                    method: 'post',
                    data: new FormData($(this)[0]),
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $(".send-notify-button").html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                        ).attr('disable', true)
                    },
                    success: function(response) {
                        $(".text-danger").remove()
                        $('.store input').removeClass('border-danger')
                        $(".send-notify-button").html("{{ __('admin.send') }}").attr('disable',
                            false)
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: '{{ __('admin.reply_successfully') }}',
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        })
                        setTimeout(function() {
                            window.location.replace(response.url)
                        }, 1000);
                    },
                    error: function(xhr) {
                        $(".send-notify-button").html("{{ __('admin.send') }}").attr('disable',
                            false)
                        $(".text-danger").remove()
                        $('.store input, .store textarea').removeClass('border-danger')

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                var errorMessage = Array.isArray(value) ? value[0] : value;
                                
                                $('.store input[name=' + key + ']').addClass('border-danger')
                                    .after(`<span class="text-danger d-block">${errorMessage}</span>`);

                                $('.store select[name=' + key + ']').addClass('border-danger')
                                    .after(`<span class="text-danger d-block">${errorMessage}</span>`);

                                $('.store textarea[name=' + key + ']').addClass('border-danger')
                                    .after(`<span class="text-danger d-block">${errorMessage}</span>`);
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.msg) {
                            Swal.fire({
                                position: 'top-start',
                                type: 'error',
                                title: xhr.responseJSON.msg || '{{ __('admin.error') }}',
                                showConfirmButton: false,
                                timer: 3000,
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        } else {
                            Swal.fire({
                                position: 'top-start',
                                type: 'error',
                                title: '{{ __('admin.error') }}',
                                showConfirmButton: false,
                                timer: 3000,
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            });
                        }
                    },
                });
            });
        });
    </script>

    <script>
        $("#anchor").on("click", function(e) {
            $("#reply form").find("input, textarea, select").val("").prop("checked", false).prop("selected", false);
        });
    </script>

    <script src="{{ asset('admin/app-assets/vendors/js/lightbox/js/lightbox.min.js') }}"></script>
    <script>
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'fadeDuration': 200
        });
    </script>
@endsection
