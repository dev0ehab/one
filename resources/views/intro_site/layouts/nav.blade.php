<body class="intro-body">
    <header class="intro-header">
        <div class="container intro-header__inner">
            <div class="d-flex justify-content-between align-items-center flex-grow-1" style="gap:12px;min-width:0">
                <a href="{{route('intro')}}" class="intro-brand">
                    <img loading="lazy" src="{{asset($settings['intro_logo'])}}" class="the-logo intro-brand__logo" alt="{{ $settings['intro_name'] }}">
                </a>
                <ul class="d-flex align-items-center justify-content-center nav_bar flex-grow-1">
                    <li><a href="{{route('intro')}}#home" data-scroll="home">{{__('intro_site.home')}}</a></li>
                    <li><a href="{{route('intro')}}#about_us" data-scroll="about_us">{{__('intro_site.who_us')}}</a></li>
                    <li><a href="{{route('intro')}}#our_service" data-scroll="our_service">{{__('intro_site.our_services')}}</a></li>
                    <li><a href="{{route('intro')}}#how_work" data-scroll="how_work">{{__('intro_site.who_we_work')}}</a></li>
                    <li><a href="{{route('intro')}}#fqs" data-scroll="fqs">{{__('intro_site.fqs')}}</a></li>
                    <li><a href="{{route('intro')}}#contact_us" data-scroll="contact_us">{{__('intro_site.contact_us')}}</a></li>
                </ul>
            </div>
            <div class="intro-header__actions">
                <div class="dropdown intro-lang">
                    <button class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        {{ lang() == 'ar' ? 'English' : 'العربية' }} <i class="fas fa-globe"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a href="{{url('lang/ar')}}" class="dropdown-item" type="button">عربي</a>
                        <a href="{{url('lang/en')}}" class="dropdown-item" type="button">English</a>
                    </div>
                </div>
                <div class="close-open-nav" aria-label="{{ lang() == 'ar' ? 'القائمة' : 'Menu' }}">
                    <div>
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </header>
