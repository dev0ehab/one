<section class="sec-padd intro-partners">
    <div class="container">
        <header class="intro-section-head the_title">
            <h2 class="intro-section-title">{{ __('intro_site.partners') }}</h2>
            <p class="grey-color">{{ $settings['partners_text'] }}</p>
        </header>

        <div class="owl-carousel owl-theme owl-brands intro-partners__track">
            @foreach ($partners as $partner)
                <div class="item">
                    <img loading="lazy" src="{{ $partner->image }}" alt="">
                </div>
            @endforeach
        </div>
    </div>
</section>
