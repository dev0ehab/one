<section class="sec-padd bacg_section2 intro-faq" id="fqs">
    <div class="container">
        <header class="intro-section-head the_title">
            <h2 class="intro-section-title">{{ __('intro_site.fqs') }}</h2>
            <p class="grey-color">{{ $settings['fqs_text'] }}</p>
        </header>
        <ul class="nav nav-pills mb-4 justify-content-center flex-wrap" id="pills-tab" role="tablist">
            @foreach ($fqsCategories as $key => $category)
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="pills-{{ $category->id }}-tab"
                        data-toggle="pill" href="#pills-{{ $category->id }}" role="tab"
                        aria-controls="pills-{{ $category->id }}"
                        aria-selected="{{ $key == 0 ? 'true' : 'false' }}">{{ $category->title }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content" id="pills-tabContent">
            @foreach ($fqsCategories as $key => $category)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="pills-{{ $category->id }}"
                    role="tabpanel" aria-labelledby="pills-{{ $category->id }}-tab">

                    <div class="accordion" id="accordionCat{{ $category->id }}">
                        @foreach ($category->questions as $fqsKey => $fqs)
                            <div class="card">
                                <div class="card-header" id="heading-{{ $fqs->id }}">
                                    <button class="{{ $fqsKey === 0 ? '' : 'collapsed' }}" type="button"
                                        data-toggle="collapse" data-target="#collapse_{{ $fqs->id }}"
                                        aria-expanded="{{ $fqsKey === 0 ? 'true' : 'false' }}"
                                        aria-controls="collapse_{{ $fqs->id }}">
                                        {{ $fqsKey + 1 }} — {{ $fqs->title }}
                                    </button>
                                </div>
                                <div id="collapse_{{ $fqs->id }}"
                                    class="collapse {{ $fqsKey === 0 ? 'show' : '' }}"
                                    aria-labelledby="heading-{{ $fqs->id }}"
                                    data-parent="#accordionCat{{ $category->id }}">
                                    <div class="card-body">{{ $fqs->description }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    </div>
</section>
