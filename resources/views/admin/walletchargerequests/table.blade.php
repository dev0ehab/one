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
                <th>{{ __('admin.user_type') }}</th>
                <th>{{ __('admin.user_name') }}</th>
                {{-- <th>{{ __('admin.amount') }}</th> --}}
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.created_at') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($walletchargerequests as $request)
                <tr class="delete_row">
                <td class="text-center">
                    <label class="container-checkbox">
                    <input type="checkbox" class="checkSingle" id="{{ $request->id }}">
                    <span class="checkmark"></span>
                    </label>
                </td>
                <td>
                    @if($request->wallet->walletable_type == 'App\Models\Client')
                        <span class="badge badge-info">{{ __('admin.client') }}</span>
                    @elseif($request->wallet->walletable_type == 'App\Models\Provider')
                        <span class="badge badge-primary">{{ __('admin.provider') }}</span>
                    @else
                        <span class="badge badge-secondary">{{ __('admin.unknown') }}</span>
                    @endif
                </td>
                <td>{{ $request->wallet->walletable->name ?? __('admin.unknown') }}</td>
                {{-- <td>{{ $request->amount }} {{ __('site.currency') }}</td> --}}
                <td>
                    @if ($request->status == 'pending')
                        <span class="badge badge-warning">{{ __('admin.pending') }}</span>
                    @elseif ($request->status == 'approved')
                        <span class="badge badge-success">{{ __('admin.approved') }}</span>
                    @else
                        <span class="badge badge-danger">{{ __('admin.rejected') }}</span>
                    @endif
                </td>
                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                <td class="product-action">
                    <span class="text-primary"><a
                        href="{{ route('admin.wallet-charge-requests.show', ['id' => $request->id]) }}"><i
                        class="feather icon-eye"></i></a></span>
                    
                    @if($request->status == 'pending')
                        <span class="text-success approve-request" data-id="{{ $request->id }}" style="cursor: pointer;">
                            <i class="feather icon-check"></i>
                        </span>
                        <span class="text-danger reject-request" data-id="{{ $request->id }}" style="cursor: pointer;">
                            <i class="feather icon-x"></i>
                        </span>
                    @endif
                </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($walletchargerequests->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{asset('/storage/images/no_data.png')}}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo ;margin-right: 35px">{{__('admin.there_are_no_matches_matching')}}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($walletchargerequests->count() > 0 && $walletchargerequests instanceof \Illuminate\Pagination\AbstractPaginator )
    <div class="d-flex justify-content-center mt-3">
        {{$walletchargerequests->links('pagination::bootstrap-4')}}
    </div>
@endif
{{-- pagination  links div --}}
