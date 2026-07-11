<div class="position-relative">
    {{-- table loader  --}}
    <div class="table_loader" >
        {{__('admin.loading')}}
    </div>
    {{-- table loader  --}}
    
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
                <th>{{ __('admin.name') }}</th>
                <th>{{ __('admin.email') }}</th>
                <th>{{ __('admin.phone') }}</th>
                <th>{{ __('admin.ban_status') }}</th>
                <th>{{ __('admin.activation') }}</th>
                <th>{{ __('admin.city') }}</th>
                <th>{{ __('admin.created_at') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clients as $client)
                <tr class="delete_row">
                <td class="text-center">
                    <label class="container-checkbox">
                    <input type="checkbox" class="checkSingle" id="{{ $client->id }}">
                    <span class="checkmark"></span>
                    </label>
                </td>
                <td><img src="{{$client->image}}" width="50px" height="50px" alt=""></td>
                <td>{{ $client->name }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ $client->phone }}</td>
                <td>
                    @if ($client->is_blocked)
                        <span class="btn btn-sm round btn-outline-danger">
                            {{ __('admin.prohibited')  }} <i class="la la-close font-medium-2"></i>
                        </span>
                        <span class="btn btn-sm round btn-outline-success block_user" data-id="{{$client->id}}">{{__('admin.unblock')}}</span>
                    @else
                        <span class="btn btn-sm round btn-outline-success">
                            {{ __('admin.unspoken') }} <i class="la la-check font-medium-2"></i>
                        </span>
                        <span class="btn btn-sm round btn-outline-danger block_user" data-id="{{$client->id}}">{{__('admin.block')}}</span>
                    @endif
                </td>
                <td>
                    @if ($client->is_active)
                    <span class="btn btn-sm round btn-outline-success">
                        {{ __('admin.activate') }} <i class="la la-close font-medium-2"></i>
                    </span>
                    @else
                    <span class="btn btn-sm round btn-outline-danger">
                        {{ __('admin.dis_activate') }} <i class="la la-check font-medium-2"></i>
                    </span>
                    @endif
                </td>
                <td>{{ $client->mainLocation ? ($client->mainLocation->city->getTranslations('name')[app()->getLocale()] ?? $client->mainLocation->city->name) : '-' }}</td>
                <td>{{ $client->created_at->format('Y-m-d H:i') }}</td>
                <td class="product-action">
                    <span class="text-primary"><a
                        href="{{ route('admin.clients.show', ['id' => $client->id]) }}"><i
                        class="feather icon-eye"></i></a></span>
                    <span class="action-edit text-primary"><a
                        href="{{ route('admin.clients.edit', ['id' => $client->id]) }}"><i
                        class="feather icon-edit"></i></a></span>
                    @if (Route::has('admin.orders.index'))
                    <span class="text-success"><a
                        href="{{ route('admin.orders.index', $client->id) }}"><i
                        class="feather icon-shopping-cart"></i></a></span>
                    @endif
                    @if (Route::has('admin.complains.index'))
                    <span class="text-warning"><a
                        href="{{ route('admin.complains.index', $client->id) }}"><i
                        class="feather icon-message-circle"></i></a></span>
                    @endif
                    @if (Route::has('admin.reservations.index'))
                    <span class="text-info"><a
                        href="{{ route('admin.reservations.index', $client->id) }}"><i
                        class="feather icon-clock"></i></a></span>
                    @endif
                    @if($client->is_notify)
                        <span data-toggle="modal" data-target="#notify" class="text-info notify"
                              data-id="{{ $client->id }}"
                              data-url="{{ url('admins/clients/notify') }}"><i
                                    class="feather icon-bell"></i></span>
                    @endif
                    {{-- <span data-toggle="modal" data-target="#mail" class="text-info mail"
                        data-id="{{ $client->id }}"
                        data-url="{{ url('admins/clients/notify') }}"><i
                        class="feather icon-mail"></i></span> --}}
                   <span class="delete-row text-danger"
                       data-url="{{ url('admin/clients/' . $client->id) }}"><i
                        class="feather icon-trash"></i></span>
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($clients->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($clients->count() > 0 && $clients instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$clients->links('pagination::bootstrap-4')}}
    </div>
@endif
{{-- pagination  links div --}}

