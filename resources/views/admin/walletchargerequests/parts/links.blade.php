<div class="row">
    <div class="col-12">
        <div class="profile-header mb-2 mt-5">

            <div class="relative">
                <div class="cover-container">
                </div>
                <div class="profile-img-container d-flex align-items-center justify-content-between">
                    @if($row->image)
                        <img loading="lazy" src="{{ $row->image }}" class="rounded-circle img-border box-shadow-1"
                            alt="Request Image">
                    @else
                        <div class="rounded-circle img-border box-shadow-1 d-flex align-items-center justify-content-center" style="width: 85px; height: 85px; background-color: #f8f9fa;">
                            <i class="feather icon-credit-card" style="font-size: 2rem; color: #6c757d;"></i>
                        </div>
                    @endif
                    <div class="float-right">
                        <span class="badge badge-lg 
                            @if($row->status == 'pending') badge-warning
                            @elseif($row->status == 'approved') badge-success
                            @else badge-danger @endif">
                            {{ __('admin.' . $row->status) }}
                        </span>
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
                                <a data-href="{{ route('admin.wallet-charge-requests.show' , ['id' =>$row->id , 'type' => 'main_data']) }}"
                                    class="nav-link font-small-3">{{ __('admin.main_data') }} </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
