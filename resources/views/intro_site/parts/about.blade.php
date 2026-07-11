<section class="sec-padd intro-about" id="about_us">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="intro-about__copy info_JM">
                    <h2 class="text-white mb-20">{{ __('intro_site.info_about') }} {{ $settings['intro_name'] }}</h2>
                    <div class="intro-about__lead grey-color">{!! $settings['intro_about'] !!}</div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="intro-about__visual img_info_JM">
                    <img loading="lazy" src="{{ $settings['about_image_1'] }}" alt="">
                    <img loading="lazy" src="{{ $settings['about_image_2'] }}" alt="">
                </div>
            </div>
        </div>
    </div>
</section>
