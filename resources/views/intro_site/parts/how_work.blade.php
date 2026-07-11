<section class="sec-padd" id="how_work">
    <div class="container">
        <header class="intro-section-head the_title">
            <h2 class="intro-section-title">{{ __('intro_site.how_work') }} {{ $settings['intro_name'] }}</h2>
            <p class="grey-color">{{ $settings['how_work_text'] }}</p>
        </header>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <div class="intro-how__strip how_work">
                    @foreach ($howWorks as $howWork)
                        <div class="intro-how__item">
                            <img loading="lazy" src="{{ $howWork->image }}" alt="{{ $howWork->title }}">
                            <div class="intro-how__caption">{{ $howWork->title }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
