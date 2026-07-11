<div class="card">
    <div class="card-header">
        <h4 class="mb-0">{{ __('admin.provider_ads') }}</h4>
    </div>
    <div class="card-body">
        @include('admin.providers.parts.ads_table', ['ads' => $ads])
    </div>
</div>
