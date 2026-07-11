<div class="position-relative">
    {{-- table loader --}}
    <div class="table_loader">
        {{ __('admin.loading') }}
    </div>
    {{-- table loader --}}
    {{-- table content --}}
    <table class="table " id="tab">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('admin.image') }}</th>
                <th>{{ __('admin.category_name') }} - {{ __('site.name_en') }}</th>
                <th>{{ __('admin.category_name') }} - {{ __('site.name_ar') }}</th>
                <th>{{ __('admin.control') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($provider_categories as $providerCategory)
                <tr class="delete_provider_category">
                    <td>{{ $loop->iteration }}</td>
                    <td><img width="50" height="50" src="{{ $providerCategory->image }}"
                            alt="{{ $providerCategory->getTranslations('name')['en'] ?? $providerCategory->name }}"></td>
                    <td>{{ $providerCategory->getTranslations('name')['en'] ?? '-' }}</td>
                    <td>{{ $providerCategory->getTranslations('name')['ar'] ?? '-' }}</td>
                    <td class="product-action">
                        <span class="action-show text-info">
                            <a href="{{ route('admin.provider-categories.show', ['id' => $providerCategory->id]) }}">
                                <i class="feather icon-eye"></i>
                            </a>
                        </span>
                        <span class="action-edit text-primary">
                            <a href="{{ route('admin.provider-categories.edit', ['id' => $providerCategory->id]) }}">
                                <i class="feather icon-edit"></i>
                            </a>
                        </span>
                        <span class="delete-row text-danger" data-url="{{ url('admin/provider-categories/' . $providerCategory->id) }}">
                            <i class="feather icon-trash"></i>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- table content --}}
    {{-- no data found div --}}
    @if ($provider_categories->count() == 0)
        <div class="d-flex flex-column w-100 align-center mt-4">
            <img src="{{ asset('/storage/images/no_data.png') }}" width="200px" style="" alt="">
            <span class="mt-2"
                style="font-family: cairo ;margin-right: 35px">{{ __('admin.there_are_no_matches_matching') }}</span>
        </div>
    @endif
    {{-- no data found div --}}

</div>
{{-- pagination links div --}}
@if ($provider_categories->count() > 0 && $provider_categories instanceof \Illuminate\Pagination\AbstractPaginator)
    <div class="d-flex justify-content-center mt-3">
        {{ $provider_categories->links() }}
    </div>
@endif
{{-- pagination links div --}}

