<section class="sec-padd intro-contact" id="contact_us">
    <div class="container">
        <header class="intro-section-head the_title">
            <h2 class="intro-section-title">{{ __('intro_site.contact_us') }}</h2>
            <p class="grey-color">{{ $settings['contact_text'] }}</p>
        </header>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="intro-contact__panel form-content">
                    <form action="{{ url('send-message') }}" enctype="multipart/form-data" method="POST"
                        class="send-message store form-horizontal parsley" novalidate>
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" required
                                placeholder="{{ __('site.name') }}"
                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                        </div>
                        <div class="form-group">
                            <input type="number" name="phone" class="form-control" required
                                placeholder="{{ __('site.phone') }}"
                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}">
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control" required
                                placeholder="{{ __('site.email') }}"
                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}"
                                data-parsley-type-message="{{ __('admin.email_formula_is_incorrect') }}">
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" name="message" required
                                placeholder="{{ __('site.write_here') }}"
                                data-parsley-required-message="{{ __('admin.this_field_is_required') }}"></textarea>
                        </div>

                        <button type="submit" class="btn-main">{{ __('site.send_message') }} <i
                                class="fas fa-long-arrow-alt-left"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
