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
                @include('admin.walletchargerequests.parts.links')

                <section id="profile-info">
                    <div class="row">
                        <div class="col-lg-8 col-12 refrshed-data ">
                            @include('admin.walletchargerequests.parts.main_data')
                        </div>

                        <div class="col-lg-4 col-12">
                            @include('admin.walletchargerequests.parts.actions')
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>

<script>
    $('.store input').attr('disabled', true)
    $('.store textarea').attr('disabled', true)
    $('.store select').attr('disabled', true)
</script>
<script>
$(document).ready(function () {
    // Automatically select the first tab on page load
    $('.show-details-links li a').first().addClass('active');

    $(document).on('click', '.show-details-links li a', function (e) {
        e.preventDefault();

        var url = $(this).data('href');

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

    // Approve request
    $(document).on('click', '.approve-request', function(e) {
        e.preventDefault();
        var requestId = $(this).data('id');
        var button = $(this);
        
        Swal.fire({
            title: "{{ __('admin.approve_request') }}",
            text: "{{ __('admin.enter_amount_to_approve') }}",
            type: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: '{{ __('admin.approve') }}',
            confirmButtonClass: 'btn btn-success',
            cancelButtonText: '{{ __('admin.cancel') }}',
            cancelButtonClass: 'btn btn-danger ml-1',
            buttonsStyling: false,
            input: 'number',
            inputPlaceholder: '{{ __("admin.amount") }}',
            inputAttributes: {
                min: '1',
                step: 'any'
            },
            inputValidator: (value) => {
                if (!value) {
                    return '{{ __("admin.amount_required") }}';
                }
                if (parseFloat(value) <= 0) {
                    return '{{ __("admin.amount_must_be_greater_than_zero") }}';
                }
            }
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '{{ route("admin.wallet-charge-requests.approve", ":id") }}'.replace(':id', requestId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: result.value
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
                        button.html('{{ __("admin.approve") }}').prop('disabled', false);
                    }
                });
            }
        });
    });

    // Reject request
    $(document).on('click', '.reject-request', function(e) {
        e.preventDefault();
        var requestId = $(this).data('id');
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
                    url: '{{ route("admin.wallet-charge-requests.reject", ":id") }}'.replace(':id', requestId),
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
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
                        button.html('{{ __("admin.reject") }}').prop('disabled', false);
                    }
                });
            }
        });
    });
});
</script>
@endsection