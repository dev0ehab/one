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
                        <a href="{{ route('admin.clients.edit', $row->id) }}"
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
                                <a data-href="{{ route('admin.clients.show' , ['id' =>$row->id , 'type' => 'main_data']) }}"
                                    class="nav-link font-small-3">{{ __('admin.main_data') }} </a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.clients.show' , ['id' =>$row->id , 'type' => 'wallet']) }}"
                                    class="nav-link font-small-3">{{ __('admin.wallet') }} (<span
                                        class="text-success available_balance"> {{ round($row->wallet?->balance ?? 0, 2) . ' ' . __('site.currency')}} </span>)</a>
                            </li>

                            <li class="nav-item px-sm-0">
                                <a data-href="{{ route('admin.clients.show' , ['id' =>$row->id , 'type' => 'points']) }}"
                                    class="nav-link font-small-3">{{ __('admin.points') }} (<span
                                        class="text-warning current_points"> {{ $row->points ?? 0 }} </span>)</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
