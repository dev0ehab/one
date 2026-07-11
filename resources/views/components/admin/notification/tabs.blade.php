<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center active" id="notify-tab" data-toggle="tab" href="#notify"
            aria-controls="notify" role="tab" aria-selected="true">
            <i class="feather icon-bell mr-25"></i><span
                class="d-none d-sm-block">{{ __('admin.send_notification') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center" id="sms-tab" data-toggle="tab" href="#sms"
            aria-controls="sms" role="tab" aria-selected="false">
            <i class="feather icon-phone mr-25"></i><span class="d-none d-sm-block">{{ __('admin.send_sms') }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link d-flex align-items-center" id="email-tab" data-toggle="tab" href="#email"
            aria-controls="email" role="tab" aria-selected="false">
            <i class="feather icon-mail mr-25"></i><span class="d-none d-sm-block">{{ __('admin.send_email') }}</span>
        </a>
    </li>
</ul>
