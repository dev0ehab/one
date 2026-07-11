<div class="position-relative">
    <div class="table_loader">{{ __('admin.loading') }}</div>

    <table class="table" id="tab">
        <thead>
            <tr>
                <th>
                    <label class="container-checkbox">
                        <input type="checkbox" value="value1" name="name1" id="checkedAll">
                        <span class="checkmark"></span>
                    </label>
                </th>
                <th>#</th>
                <th>{{ __('admin.package_name') }} (EN)</th>
                <th>{{ __('admin.package_name') }} (AR)</th>
                <th>{{ __('admin.package_price') }}</th>
                <th>{{ __('admin.ads_count') }}</th>
                <th>{{ __('admin.duration_days') }}</th>
                <th>{{ __('admin.sort_order') }}</th>
                <th>{{ __('admin.status') }}</th>
                <th>{{ __('admin.package_subscriptions_count') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $package)
                <tr class="delete_package">
                    <td class="text-center">
                        <label class="container-checkbox">
                            <input type="checkbox" class="checkSingle" id="{{ $package->id }}">
                            <span class="checkmark"></span>
                        </label>
                    </td>
                    <td>{{ $package->id }}</td>
                    <td>{{ $package->getTranslation('name', 'en') }}</td>
                    <td>{{ $package->getTranslation('name', 'ar') }}</td>
                    <td>{{ number_format($package->price, 2) }}</td>
                    <td>{{ $package->ads_count }}</td>
                    <td>{{ $package->duration_days }}</td>
                    <td>{{ $package->sort }}</td>
                    <td>
                        @if ($package->is_active)
                            <span class="badge badge-success">{{ __('admin.active') }}</span>
                        @else
                            <span class="badge badge-secondary">{{ __('admin.inactive') }}</span>
                        @endif
                    </td>
                    <td>{{ $package->subscriptions_count }}</td>
                    <td class="product-action">
                        <span class="action-show text-info">
                            <a href="{{ route('admin.packages.show', ['id' => $package->id]) }}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        <span class="action-edit text-primary">
                            <a href="{{ route('admin.packages.edit', ['id' => $package->id]) }}">
                                <i class="feather icon-edit"></i>
                            </a>
                        </span>
                        <span class="delete-row text-danger" data-url="{{ url('admin/packages/' . $package->id) }}">
                            <i class="feather icon-trash"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($packages->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" alt="">
            <span class="mt-2" style="font-family: cairo; margin-right: 35px">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
</div>

@if ($packages->count() > 0 && $packages instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $packages->links() }}
    </div>
@endif
