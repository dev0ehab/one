<div class="position-relative">
    {{-- table loader  --}}
    <div class="table_loader">
        {{ __('admin.loading') }}
    </div>
    {{-- table loader  --}}

    {{-- table content --}}
    <table class="table" id="tab">
        <thead>
            <tr>
                <th>{{ __('admin.pharmacy_name') }}</th>
                <th>{{ __('admin.commercial_name') }}</th>
                <th>{{ __('admin.phone') }}</th>
                <th>{{ __('admin.request_date') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entity_updates as $entityUpdate)
                @php
                    $pharmacy = $entityUpdate->updateable;
                @endphp
                <tr class="delete_row">
                    <td>
                        <strong>{{ $pharmacy->name ?? __('admin.no_name') }}</strong>
                    </td>
                    <td>
                        <strong>{{ $pharmacy->getTranslation('commercial_name', app()->getLocale()) ?: __('admin.no_commercial_name') }}</strong>
                    </td>
                    <td>
                        <span dir="ltr">{{ $pharmacy->phone ?? '' }}</span>
                    </td>
                    <td>{{ $entityUpdate->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if (is_null($entityUpdate->is_approved))
                            <span class="btn btn-sm round btn-outline-warning">
                                {{ __('admin.pending') }} <i class="la la-clock-o font-medium-2"></i>
                            </span>
                        @elseif($entityUpdate->is_approved == 1)
                            <span class="btn btn-sm round btn-outline-success">
                                {{ __('admin.approved') }} <i class="la la-check font-medium-2"></i>
                            </span>
                        @elseif($entityUpdate->is_approved == 0)
                            <span class="btn btn-sm round btn-outline-danger">
                                {{ __('admin.rejected') }} <i class="la la-close font-medium-2"></i>
                            </span>
                        @endif
                    </td>
                    <td class="product-action">
                        <span class="text-primary">
                            <a href="{{ route('admin.pharmacy_update_requests.show', ['id' => $entityUpdate->id]) }}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($entity_updates->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" style="" alt="">
            <span class="mt-2" style="font-family: cairo; margin-right: 35px">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination  links div --}}
@if ($entity_updates->count() > 0 && $entity_updates instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $entity_updates->links('pagination::bootstrap-4') }}
    </div>
@endif
{{-- pagination  links div --}}

