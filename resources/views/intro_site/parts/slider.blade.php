<div class="owl-carousel owl-theme owl-index intro-hero" id="home">
    @foreach ($sliders as $slider)
        <div class="item">
            <section class="intro-hero-slide">
                <div class="intro-hero-slide__grid">
                    <div class="intro-hero-slide__text">
                        <span class="intro-hero-slide__eyebrow">{{ $settings['intro_name'] }}</span>
                        <h3>{{ $slider->title }}</h3>
                        <p>{{ $slider->description }}</p>
                    </div>
                    <div class="intro-hero-slide__media">
                        <img loading="lazy" src="{{ $slider->image }}" alt="{{ $slider->title }}">
                    </div>
                </div>
            </section>
        </div>
    @endforeach
</div>
