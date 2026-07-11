<section class="sec-padd bacg_section" id="our_service">
    <div class="container">
        <header class="intro-section-head the_title">
            <h2 class="intro-section-title">{{ __('intro_site.our_services') }}</h2>
            <p>{{ $settings['services_text'] }}</p>
        </header>
        <div class="intro-services__grid">
            @foreach ($services as $service)
                <article class="box-servess intro-service-card">
                    <div class="intro-service-card__icon">
                        <img loading="lazy" src="{{ asset('intro_site/imgs/ser.png') }}" alt="">
                    </div>
                    <h6 class="f-b">{{ $service->title }}</h6>
                    <p class="grey-color">{{ $service->description }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
