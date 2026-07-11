<div class="position-relative">
    <div class="table_loader">{{ __('admin.loading') }}</div>

    <table class="table" id="tab">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('admin.image') }}</th>
                <th>{{ __('admin.provider') }}</th>
                <th>العنوان</th>
                <th>{{ __('admin.approval_status') }}</th>
                <th>{{ __('admin.created_at') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ads as $ad)
            <tr>
                <td>{{ $ad->id }}</td>
                <td>
                    @if($ad->images->isNotEmpty())
                        <img src="{{ $ad->images->first()->image }}" width="55" height="55"
                             class="rounded" style="object-fit:cover;" alt="">
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($ad->provider)
                        <strong>{{ $ad->provider->trade_name }}</strong>
                    @endif
                </td>
                <td>{{ $ad->title }}</td>
                <td>
                    @if($ad->status === 'approved')
                        <span class="btn btn-sm round btn-outline-success">
                            {{ __('admin.accepted') }} <i class="la la-check"></i>
                        </span>
                    @elseif($ad->status === 'pending')
                        <div class="d-flex flex-column">
                            <span class="btn btn-sm round btn-outline-warning mb-1">
                                {{ __('admin.pending') }} <i class="la la-clock-o"></i>
                            </span>
                            <button class="btn btn-sm btn-success approve-ad mb-1" data-id="{{ $ad->id }}">
                                <i class="la la-check"></i> {{ __('admin.accept') }}
                            </button>
                            <button class="btn btn-sm btn-danger reject-ad" data-id="{{ $ad->id }}">
                                <i class="la la-close"></i> {{ __('admin.reject') }}
                            </button>
                        </div>
                    @elseif($ad->status === 'rejected')
                        <span class="btn btn-sm round btn-outline-danger">
                            {{ __('admin.rejected') }} <i class="la la-close"></i>
                        </span>
                        @if($ad->reject_reason)
                            <small class="d-block text-muted mt-1">{{ Str::limit($ad->reject_reason, 50) }}</small>
                        @endif
                    @endif
                </td>
                <td>{{ $ad->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('admin.provider-ads.show', $ad->id) }}" class="text-primary">
                        <i class="feather icon-eye"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($ads->count() === 0)
        <div class="d-flex flex-column w-100 align-center mt-4 text-center">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" alt="">
            <span class="mt-2">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
</div>

@if($ads->count() > 0 && $ads instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $ads->links('pagination::bootstrap-4') }}
    </div>
@endif
