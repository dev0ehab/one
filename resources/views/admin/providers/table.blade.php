<div class="position-relative">
    {{-- table loader  --}}
    <div class="table_loader" >
        {{__('admin.loading')}}
    </div>
    {{-- table loader  --}}

    @php
        use App\Services\Chat\ChatService;
        $chatService = new ChatService();
        $admin = auth('admin')->user();
    @endphp

    {{-- table content --}}
    <table class="table " id="tab">
        <thead>
            <tr>
                <th>
                    <label class="container-checkbox">
                        <input type="checkbox" value="value1" name="name1" id="checkedAll">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th>{{ __('admin.image') }}</th>
                <th>اسم الخدمة</th>
                <th>{{ __('admin.trade_name') }}</th>
                <th>{{ __('admin.owner_name') }}</th>
                <th>{{ __('admin.phone') }}</th>
                <th>{{ __('admin.approval_status') }}</th>
                <th>{{ __('admin.ban_status') }}</th>
                <th>{{ __('admin.created_at') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($providers as $provider)
                <tr class="delete_row">
                <td class="text-center">
                    <label class="container-checkbox">
                    <input type="checkbox" class="checkSingle" id="{{ $provider->id }}">
                    <span class="checkmark"></span>
                    </label>
                </td>
                <td><img src="{{$provider->image}}" width="50px" height="50px" alt="" class="rounded-circle"></td>
                <td>
                    <strong>{{ $provider->trade_name ?: __('admin.no_name') }}</strong>
                    @if($provider->category)
                        <br><small class="text-muted">{{ $provider->category->getTranslation('name', app()->getLocale()) }}</small>
                    @endif
                </td>
                <td>{{ $provider->owner_name ?: __('admin.no_name') }}</td>
                <td>
                    <span dir="ltr">{{ $provider->phone ?? '' }}</span>

                </td>
                <td>
                    @if($provider->is_approved == 1)
                        <span class="btn btn-sm round btn-outline-success">
                            {{ __('admin.accepted') }} <i class="la la-check font-medium-2"></i>
                        </span>
                    @elseif(is_null($provider->is_approved))
                        <div class="d-flex flex-column gap-1">
                            <span class="btn btn-sm round btn-outline-warning mb-1">
                                {{ __('admin.pending') }} <i class="la la-clock-o font-medium-2"></i>
                            </span>
                            <button type="button"
                                class="btn btn-sm btn-success accept-provider mb-1"
                                data-id="{{ $provider->id }}">
                                <i class="la la-check"></i> {{ __('admin.accept') }}
                            </button>
                            <button type="button"
                                class="btn btn-sm btn-danger reject-provider"
                                data-id="{{ $provider->id }}">
                                <i class="la la-close"></i> {{ __('admin.reject') }}
                            </button>
                        </div>
                    @elseif($provider->is_approved == 0)
                        <span class="btn btn-sm round btn-outline-danger">
                            {{ __('admin.rejected') }} <i class="la la-close font-medium-2"></i>
                        </span>
                    @endif
                </td>
                <td>
                    @if ($provider->is_blocked)
                        <span class="btn btn-sm round btn-outline-danger">
                            {{ __('admin.prohibited')  }} <i class="la la-close font-medium-2"></i>
                        </span>
                        <span class="btn btn-sm round btn-outline-success block_user" data-id="{{$provider->id}}">{{__('admin.unblock')}}</span>
                    @else
                        <span class="btn btn-sm round btn-outline-success">
                            {{ __('admin.unspoken') }} <i class="la la-check font-medium-2"></i>
                        </span>
                        <span class="btn btn-sm round btn-outline-danger block_user" data-id="{{$provider->id}}">{{__('admin.block')}}</span>
                    @endif
                </td>
                <td>{{ $provider->created_at->format('Y-m-d H:i') }}</td>
                <td class="product-action">
                    <a href="{{ route('admin.providers.showRoom', ['provider' => $provider->id]) }}"
                        class="btn btn-sm btn-primary position-relative mr-1"
                        title="{{ __('admin.live_chat') }}">
                        <i class="feather icon-message-square text-white"></i>
                        @if($provider->chat)
                            @php $unseen = $chatService->getRoomUnseenMessagesCount($provider->chat, $admin); @endphp
                            @if($unseen > 0)
                                <span class="badge badge-pill badge-danger position-absolute"
                                    style="top: -6px; right: -6px;">{{ $unseen }}</span>
                            @endif
                        @endif
                    </a>
                    <span class="text-primary"><a
                        href="{{ route('admin.providers.show', ['id' => $provider->id]) }}"><i
                        class="feather icon-eye"></i></a></span>
                    <span class="action-edit text-primary"><a
                        href="{{ route('admin.providers.edit', ['id' => $provider->id]) }}"><i
                        class="feather icon-edit"></i></a></span>
                    @if($provider->is_notify)
                        <span data-toggle="modal" data-target="#notify" class="text-info notify"
                              data-id="{{ $provider->id }}"
                              data-url="{{ url('admins/providers/notify') }}"><i
                                    class="feather icon-bell"></i></span>
                    @endif
                   <span class="delete-row text-danger"
                       data-url="{{ url('admin/providers/' . $provider->id) }}"><i
                        class="feather icon-trash"></i></span>
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($providers->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($providers->count() > 0 && $providers instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$providers->links('pagination::bootstrap-4')}}
    </div>
@endif
{{-- pagination  links div --}}
