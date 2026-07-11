<div class="row">
    <div class="col-12">
        <div class="profile-header mb-2 mt-5">

            <div class="relative">
                <div class="cover-container">
                </div>
                <div class="profile-img-container d-flex align-items-center justify-content-between">
                    <img loading="lazy" src="{{ $row->image }}" class="rounded-circle img-border box-shadow-1"
                        alt="Card image">
                    <div class="float-right">
                        <a href="{{ route('admin.providers.edit', $row->id) }}"
                            class="btn btn-icon btn-icon rounded-circle btn-primary mr-1">
                            <i class="feather icon-edit-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end align-items-center profile-header-nav">
                <nav class="navbar navbar-expand-sm w-100 pr-0">
                    <button class="navbar-toggler pr-0" type="button" data-toggle="collapse"
                        data-target="navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"><i class="feather icon-align-justify"></i></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav  w-75 ml-sm-auto show-details-links">
                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'main_data']) }}"
                                    class="nav-link font-small-3">{{ __('admin.main_data') }} </a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'dashboard']) }}"
                                    class="nav-link font-small-3">{{ __('admin.statistics') }}</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'work_days']) }}"
                                    class="nav-link font-small-3">{{ __('admin.work_days') }}</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'ads']) }}"
                                    class="nav-link font-small-3">{{ __('admin.provider_ads') }}</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'packages']) }}"
                                    class="nav-link font-small-3">{{ __('admin.packages') }}</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'doctors']) }}"
                                    class="nav-link font-small-3">{{ __('admin.doctors') }}</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.providers.show' , ['id' =>$row->id , 'type' => 'rates']) }}"
                                    class="nav-link font-small-3">{{ __('admin.rates') }}</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
