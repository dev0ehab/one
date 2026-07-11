<footer class="intro-footer">
    <div class="container intro-footer__grid">
        <div class="row">
            <div class="col-md-4">
                <div class="intro-footer__brand">
                    <a href="{{ route('intro') }}">
                        <img loading="lazy" src="{{ asset($settings['intro_logo']) }}" class="logo_footer" alt="{{ $settings['intro_name'] }}">
                    </a>
                </div>
                <ul class="social-m">
                    @foreach ($socials as $social)
                        <li><a target="_blank" rel="noopener noreferrer" href="{{ $social->url }}" aria-label="روابط التواصل"><i class="{{ $social->icon }}"></i></a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="Tfooter">{{ __('intro_site.quiek_links') }}</h6>
                <ul class="link-footer">
                    <li><i class="fas fa-angle-double-left"></i> <a href="{{ route('intro') }}#home" data-scroll="home">{{ __('intro_site.home') }}</a></li>
                    <li><i class="fas fa-angle-double-left"></i> <a href="{{ route('intro') }}#contact_us" data-scroll="contact_us">{{ __('intro_site.contact_us') }}</a></li>
                    <li><i class="fas fa-angle-double-left"></i> <a href="{{ url('provider/register') }}">{{ __('intro_site.register_page') }}</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="Tfooter">{{ __('intro_site.contact_us') }}</h6>
                <ul class="link-footer">
                    <li><i class="fas fa-map-marker-alt"></i> <span>{{ $settings['intro_address'] }}</span></li>
                    <li><i class="fas fa-phone"></i> <a href="tel:{{ $settings['intro_phone'] }}">{{ $settings['intro_phone'] }}</a></li>
                    <li><i class="far fa-envelope"></i> <a href="mailto:{{ $settings['intro_email'] }}">{{ $settings['intro_email'] }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="under_footer">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-8">
                    {{ __('intro_site.recives') }} {{ \Carbon\Carbon::now()->year }} {{ __('intro_site.site') }}
                    <strong>{{ $settings['intro_name_' . lang()] }}</strong>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <a href="{{ route('IntroPrivacyPolicy') }}">{{ __('intro_site.privacy') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!--========================== Start Loading Page ======================-->

<div class="loader">
    <img loading="lazy" src="{{ asset($settings['intro_loader']) }}" alt="شعار التحميل">
</div>

<!--========================= End Loading Page =========================-->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="{{ asset('intro_site/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('intro_site/js/popper.min.js') }}"></script>
<script src="{{ asset('intro_site/js/bootstrap.min.js') }}"></script>
<!-- plugins JS -->
<script src="{{ asset('intro_site/plugins/owl-carousel/js/owl.carousel.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
    integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- my JS -->
<script src="{{ asset('intro_site/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.2/dist/parsley.js"></script>

<script>
$(document).ready(function () {
    $('.parsley').parsley();

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "newestOnTop": true,
        "positionClass": "toast-top-left",
        "preventDuplicates": true,
        "timeOut": "3600",
        "extendedTimeOut": "900",
        "showDuration": "320",
        "hideDuration": "280",
        "showMethod": "slideDown",
        "hideMethod": "fadeOut",
        "rtl": {{ lang() == 'ar' ? 'true' : 'false' }},
        "toastClass": "toast intro-toast"
    };
});

$(document).on('submit', '.send-message', function (e) {
    e.preventDefault()
    var url = $(this).attr('action')
    $.ajax({
        url: url,
        method: 'post',
        data: new FormData($(this)[0]),
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            $('.error_meassages').remove();
            toastr.success(response.message)
            $('.send-message')[0].reset()
        },
        error: function (xhr) {
            $('.error_meassages').remove();

            $.each(xhr.responseJSON.errors, function (key, value) {
                $('.send-message input[name=' + key + ']').after('<small class="form-text error_meassages text-danger">' + value + '</small>');
                $('.send-message textarea[name=' + key + ']').after('<small class="form-text error_meassages text-danger">' + value + '</small>');
            });
        },
    });
});
</script>

</body>

</html>
