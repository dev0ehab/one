@extends('admin.layout.master')

@section('css')
<link rel="stylesheet" type="text/css"
    href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
<style>
    /*========================================================
                    DARK LAYOUT
                =========================================================*/
    #user-profile .profile-img-container {
        position: absolute;
        bottom: -3rem;
        right: 10%;
        width: 80%;
    }

    #user-profile .profile-img-container img {
        border: 0.3rem solid #FFFFFF;
        height: 85px;
        width: 85px;
    }

    #user-profile #profile-info .card-header i {
        position: relative;
        top: -3px;
    }

    #user-profile #profile-info .user-like i {
        font-size: 1.7rem;
    }

    #user-profile #profile-info .suggested-block .user-page-info p {
        margin-bottom: 0;
        font-weight: 500;
    }

    #user-profile #profile-info .suggested-block i {
        cursor: pointer;
    }

    #user-profile .relative {
        position: relative;
    }

    #user-profile .profile-header-nav {
        background-color: #FFFFFF;
        padding: 0.75rem 1rem;
    }

    #user-profile .profile-header-nav .navbar {
        -webkit-box-pack: end;
        -webkit-justify-content: flex-end;
        -ms-flex-pack: end;
        justify-content: flex-end;
    }

    #user-profile .profile-header-nav .navbar .navbar-toggler {
        font-size: 1.7rem;
        color: #626262;
    }

    #user-profile .profile-header-nav .navbar .navbar-toggler:focus {
        outline: none;
    }

    #user-profile .user-latest-img {
        -webkit-transition: all 0.2s ease-in-out;
        transition: all 0.2s ease-in-out;
    }

    #user-profile .user-latest-img:hover {
        -webkit-transform: translateY(-4px) scale(1.2);
        -ms-transform: translateY(-4px) scale(1.2);
        transform: translateY(-4px) scale(1.2);
        z-index: 30;
    }

    #user-profile .block-element .spinner-border {
        border-width: 2px;
    }

    .show-details-links li a.active {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    @media only screen and (min-width: 992px) {
        #user-profile .profile-header-nav .navbar .nav-item {
            padding-right: 2.25rem !important;
            padding-left: 2.25rem !important;
        }
    }

    @media only screen and (max-width: 992px) {
        #user-profile .user-latest-img img {
            width: 100%;
        }
    }

    @media only screen and (max-width: 991px) and (min-width: 768px) {
        #user-profile .profile-header-nav .navbar .nav-item {
            padding-right: 1.5rem !important;
            padding-left: 1.5rem !important;
        }
    }
</style>
@endsection

@section('content')
<div class="app-content content m-0">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper m-0 p-0">
        <div class="content-body">
            <div id="user-profile">
                @include('admin.providers.parts.links')

                <section id="profile-info">
                    <div class="row">
                        <div class="col-lg-12 col-12 refrshed-data ">
                            @include('admin.providers.parts.main_data')
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- @endsection --}}

@section('js')
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>
@include('admin.shared.notify')

<script>
    $('.store input').attr('disabled', true)
    $('.store textarea').attr('disabled', true)
    $('.store select').attr('disabled', true)
</script>
<script>
var providerShowTabType = 'main_data';

$(document).ready(function () {
    // Automatically select the first tab on page load
    $('.show-details-links li a').first().addClass('active');

    $(document).on('click', '.delete-row', function (e) {
        e.preventDefault()
        if (confirm("{{ __('admin.are_you_want_to_continue') }}")) {
            $.ajax({
                type: "delete",
                url: $(this).data('url'),
                data: {
                    deletails: true
                },
                dataType: "json",
                success: (response) => {
                    window.location = '{{ route('admin.providers.index') }}';
                },
                error: function (response) {
                    console.error(response);
                }
            });
        }
    });

    $(document).on('click', '.show-details-links li a', function (e) {
        e.preventDefault();

        var url = $(this).data('href');
        var typeMatch = url.match(/[?&]type=([^&]+)/);
        if (typeMatch) {
            providerShowTabType = typeMatch[1];
        }

        // Remove 'active' class from all other links
        $('.show-details-links li a').removeClass('active');

        // Add 'active' class to the clicked link
        $(this).addClass('active');

        $.ajax({
            url: url,
            method: 'get',
            data: {},
            dataType: 'json',
            beforeSend: function () {
                // $(".refrshed-data").html().attr('disable', true)
            },
            success: function (response) {
                // $(".submit_button").html("{{ __('admin.add') }}").attr('disable', false)

                $(".refrshed-data").html(response.html)
            },
        });
    });
});

$(document).ready(function () {
    // When a pagination link is clicked
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        var url = $(this).attr('href') + '&type=' + providerShowTabType;

        // Make AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                // Update the table
                $('.refrshed-data').html(response.html);
            },
            error: function (xhr, status, error) {
                console.log(error); // Handle the error gracefully
            }
        });
    });

    // Accept provider
    $(document).on('click', '.accept-provider', function (e) {
        e.preventDefault();
        var providerId = $(this).data('id');
        var button = $(this);
        
        $.ajax({
            url: '{{ route('admin.providers.accept') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: providerId
            },
            dataType: 'json',
            beforeSend: function() {
                button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            },
            success: function(response) {
                Swal.fire({
                    position: 'top-start',
                    type: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            },
            error: function(xhr) {
                Swal.fire({
                    position: 'top-start',
                    type: 'error',
                    title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                    showConfirmButton: false,
                    timer: 1500,
                });
                button.html('<i class="la la-check"></i> {{ __('admin.accept') }}').prop('disabled', false);
            }
        });
    });

    // Reject provider
    $(document).on('click', '.reject-provider', function (e) {
        e.preventDefault();
        var providerId = $(this).data('id');
        var button = $(this);
        
        Swal.fire({
            title: "{{ __('admin.reject_request') }}",
            text: "{{ __('admin.are_you_sure_reject') }}",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __('admin.reject') }}',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonText: '{{ __('admin.cancel') }}',
            cancelButtonClass: 'btn btn-secondary ml-1',
            buttonsStyling: false,
            input: 'textarea',
            inputPlaceholder: '{{ __("admin.rejection_reason") }}',
            inputValidator: (value) => {
                if (!value) {
                    return '{{ __("admin.rejection_reason_required") }}';
                }
            }
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route('admin.providers.reject') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: providerId,
                        reason: result.value
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500,
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            position: 'top-start',
                            type: 'error',
                            title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        button.html('<i class="la la-close"></i> {{ __('admin.reject') }}').prop('disabled', false);
                    }
                });
            }
        });
    });

    function reloadProviderShowTab() {
        var $active = $('.show-details-links li a.active');
        if ($active.length) {
            $active.trigger('click');
        }
    }

    $(document).on('click', '.approve-ad', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');

        Swal.fire({
            title: '{{ __('admin.accept') }}',
            text: '{{ __('admin.are_you_sure') }}',
            type: 'question',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-secondary ml-1',
            confirmButtonText: '{{ __('admin.accept') }}',
            cancelButtonText: '{{ __('admin.cancel') }}',
            buttonsStyling: false,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/admin/provider-ads/' + id + '/approve',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    beforeSend: function () { btn.prop('disabled', true); },
                    success: function (response) {
                        Swal.fire({ type: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                        setTimeout(reloadProviderShowTab, 1200);
                    },
                    error: function (xhr) {
                        Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });

    $(document).on('click', '.reject-ad', function (e) {
        e.preventDefault();
        var btn = $(this);
        var id = btn.data('id');

        Swal.fire({
            title: '{{ __('admin.reject') }}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '{{ __('admin.reject') }}',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonText: '{{ __('admin.cancel') }}',
            cancelButtonClass: 'btn btn-secondary ml-1',
            buttonsStyling: false,
            input: 'textarea',
            inputPlaceholder: '{{ __("admin.rejection_reason") }}',
            inputValidator: function (value) {
                if (!value) return '{{ __("admin.rejection_reason_required") }}';
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/admin/provider-ads/' + id + '/reject',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', reason: result.value },
                    dataType: 'json',
                    beforeSend: function () { btn.prop('disabled', true); },
                    success: function (response) {
                        Swal.fire({ type: 'success', title: response.message, timer: 1500, showConfirmButton: false });
                        setTimeout(reloadProviderShowTab, 1200);
                    },
                    error: function (xhr) {
                        Swal.fire({ type: 'error', title: xhr.responseJSON?.message || '{{ __("admin.error_occurred") }}', timer: 2000 });
                        btn.prop('disabled', false);
                    }
                });
            }
        });
    });
});
</script>
@endsection
