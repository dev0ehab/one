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
                @include('admin.clients.parts.links')

                <section id="profile-info">
                    <div class="row">
                        <div class="col-lg-8 col-12 refrshed-data ">
                            @include('admin.clients.parts.main_data')
                        </div>

                        <div class="col-lg-4 col-12">
                            @include('admin.clients.parts.charge_wallet')
                            @include('admin.clients.parts.manage_points')
                            @include('admin.clients.parts.notify')
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
                    window.location = '{{ route('admin.clients.index') }}';
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

    $(document).on('submit', '.updateBalance', function (e) {
        e.preventDefault();
        var url = $(this).attr('action')
        var button = $(".updateBalance .submit-button");
        var buttonContent = button.html()
        var form = $(this);
        $('.balance-error').hide().text('');
        $.ajax({
            url: url,
            method: 'post',
            data: new FormData($(this)[0]),
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                ).attr('disabled', true)
            },
            success: (response) => {
                button.html(buttonContent).attr('disabled', false);
                $('.available_balance').html(response.balance);
                form[0].reset();
                $('.balance-error').hide().text('');
                
                // Show success alert
                Swal.fire({
                    position: 'top-start',
                    type: 'success',
                    title: '{{ __("admin.success") }}',
                    text: response.msg || '{{ __("admin.balance_updated") }}',
                    showConfirmButton: false,
                    timer: 2000,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                
                // Reload main data to update wallet balance
                if ($('.show-details-links li a.active').length > 0) {
                    var activeLink = $('.show-details-links li a.active');
                    if (activeLink.data('href').includes('main_data')) {
                        activeLink.trigger('click');
                    }
                }
            },
            error: function (xhr) {
                button.html(buttonContent).attr('disabled', false);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMessages = [];
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessages.push(value[0]);
                    });
                    $('.balance-error').text(errorMessages.join('<br>')).show();
                    return;
                }
                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    // Show validation error message
                    $('.balance-error').text(xhr.responseJSON.msg).show();
                    // Also show alert for better visibility
                    Swal.fire({
                        position: 'top-start',
                        type: 'error',
                        title: '{{ __("admin.error") }}',
                        text: xhr.responseJSON.msg,
                        showConfirmButton: false,
                        timer: 3000,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                    return;
                }
                $('.balance-error').text('{{ __("admin.something_went_wrong") }}').show();
            }
        });
    });

    // Update points
    $(document).on('submit', '.updatePoints', function (e) {
        e.preventDefault();
        var url = $(this).attr('action')
        var button = $(".updatePoints .submit-button");
        var buttonContent = button.html()
        var form = $(this);
        $('.points-error').hide().text('');
        $.ajax({
            url: url,
            method: 'post',
            data: new FormData($(this)[0]),
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
                button.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                ).attr('disabled', true)
            },
            success: (response) => {
                button.html(buttonContent).attr('disabled', false);
                $('.current_points').html(response.points);
                form[0].reset();
                $('.points-error').hide().text('');
                
                // Show success alert
                Swal.fire({
                    position: 'top-start',
                    type: 'success',
                    title: '{{ __("admin.success") }}',
                    text: response.msg || '{{ __("admin.points_updated") }}',
                    showConfirmButton: false,
                    timer: 2000,
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                });
                
                // Reload main data to update points
                if ($('.show-details-links li a.active').length > 0) {
                    var activeLink = $('.show-details-links li a.active');
                    if (activeLink.data('href').includes('main_data')) {
                        activeLink.trigger('click');
                    }
                }
            },
            error: function (xhr) {
                button.html(buttonContent).attr('disabled', false);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errorMessages = [];
                    $.each(xhr.responseJSON.errors, function(key, value) {
                        errorMessages.push(value[0]);
                    });
                    $('.points-error').text(errorMessages.join('<br>')).show();
                    return;
                }
                if (xhr.responseJSON && xhr.responseJSON.msg) {
                    // Show validation error message
                    $('.points-error').text(xhr.responseJSON.msg).show();
                    // Also show alert for better visibility
                    Swal.fire({
                        position: 'top-start',
                        type: 'error',
                        title: '{{ __("admin.error") }}',
                        text: xhr.responseJSON.msg,
                        showConfirmButton: false,
                        timer: 3000,
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                    return;
                }
                $('.points-error').text('{{ __("admin.something_went_wrong") }}').show();
            }
        });
    });
});

$(document).ready(function () {
    // When a pagination link is clicked
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();

        var url = $(this).attr('href') + '&type=wallet'; // Get the URL for the next page

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
});
</script>
@endsection
