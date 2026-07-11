@extends('admin.layout.master')

@section('css')
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">
    <style>
        /* Dashboard Style - Matching Admin Dashboard */
        .info-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            background: #2196F3;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .info-card.success {
            border-left: 4px solid #28c76f;
        }

        .info-card.warning {
            border-left: 4px solid #ff9f43;
        }

        .info-card.danger {
            border-left: 4px solid #ea5455;
        }

        .info-card .info-label {
            font-size: 0.875rem;
            color: #ffffff;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .info-card .info-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .comparison-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .comparison-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border-bottom: 1px solid #2196F3;
            transition: all 0.2s;
        }

        .comparison-row:hover {
            background-color: #1565C0;
        }

        .comparison-row:last-child {
            border-bottom: none;
        }

        .comparison-header {
            background: #475569;
            color: #2196F3;
            font-weight: 600;
            padding: 1rem;
            text-align: center;
        }

        .comparison-cell {
            padding: 1rem;
            border-right: 1px solid #2196F3;
            display: flex;
            align-items: center;
        }

        .comparison-cell:last-child {
            border-right: none;
        }

        .comparison-cell.field-name {
            background-color: #1565C0;
            font-weight: 600;
            color: #ffffff;
        }

        .value-badge {
            display: inline-block;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .value-changed {
            background-color: #FFA726;
            color: #ffffff;
        }

        .value-unchanged {
            background-color: #66BB6A;
            color: #ffffff;
        }

        .value-current {
            background-color: #42A5F5;
            color: #ffffff;
        }

        .value-requested {
            background-color: #EF5350;
            color: #ffffff;
        }

        .section-title {
            color: #1565C0;
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #2196F3;
        }

        .relation-card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .relation-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .relation-header {
            background: #475569;
            color: #2196F3;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .json-viewer {
            background: #1e1e2e;
            color: #2196F3;
            padding: 1.5rem;
            border-radius: 8px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            line-height: 1.6;
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #2d2d3e;
        }

        .json-viewer pre {
            margin: 0;
            color: #2196F3;
        }

        .nav-tabs {
            border-bottom: 2px solid #2196F3;
        }

        .nav-tabs .nav-link {
            color: #1565C0;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 1rem 1.5rem;
            transition: all 0.3s;
            font-weight: 500;
        }

        .nav-tabs .nav-link:hover {
            border-bottom-color: #475569;
            color: #475569;
            background-color: #1565C0;
        }

        .nav-tabs .nav-link.active {
            color: #475569;
            border-bottom-color: #475569;
            font-weight: 600;
            background-color: transparent;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: #2196F3;
            padding: 1.5rem;
            border-top: 1px solid #2196F3;
            box-shadow: 0 -2px 8px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-header {
            background: #475569;
            color: #2196F3;
            border-radius: 8px 8px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .card-header h4 {
            color: #2196F3;
            margin: 0;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: #1565C0;
        }

        .thead-light {
            background: #1565C0;
            color: #ffffff;
        }

        .image-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .image-preview:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(71, 85, 105, 0.3);
        }

        .image-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .image-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
        }

        .image-modal-content {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .image-modal-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #1565C0;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
        }

        .image-modal-close:hover {
            color: #475569;
        }
    </style>
@endsection

@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12">
                {{-- Header Card --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="feather icon-edit-2 mr-1"></i>
                            {{ __('admin.provider_update_request_details') }}
                        </h4>
                        <a href="{{ route('admin.provider_update_requests.index') }}" class="btn btn-sm btn-secondary">
                            <i class="feather icon-arrow-right"></i> {{ __('admin.back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-card">
                                    <p class="info-label">{{ __('admin.provider_name') }}</p>
                                    <p class="info-value">{{ $currentProvider->trade_name ?? __('admin.no_name') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <p class="info-label">{{ __('admin.request_date') }}</p>
                                    <p class="info-value">{{ $entityUpdate->created_at->format('Y-m-d H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card {{ is_null($entityUpdate->is_approved) ? 'warning' : ($entityUpdate->is_approved == 1 ? 'success' : 'danger') }}">
                                    <p class="info-label">{{ __('admin.status') }}</p>
                                    <p class="info-value">
                                        @if (is_null($entityUpdate->is_approved))
                                            <span class="badge badge-warning">{{ __('admin.pending') }}</span>
                                        @elseif($entityUpdate->is_approved == 1)
                                            <span class="badge badge-success">{{ __('admin.approved') }}</span>
                                        @elseif($entityUpdate->is_approved == 0)
                                            <span class="badge badge-danger">{{ __('admin.rejected') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabs Navigation --}}
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="changes-tab" data-toggle="tab" href="#changes" role="tab"
                                    aria-controls="changes" aria-selected="true">
                                    <i class="feather icon-git-compare mr-1"></i>
                                    {{ __('admin.changes_comparison') }}
                                </a>
                            </li>
                            @if (isset($allData['relations']) && !empty($allData['relations']))
                                <li class="nav-item">
                                    <a class="nav-link" id="relations-tab" data-toggle="tab" href="#relations" role="tab"
                                        aria-controls="relations" aria-selected="false">
                                        <i class="feather icon-link mr-1"></i>
                                        {{ __('admin.relations') }}
                                    </a>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content mt-3">
                            {{-- Changes Comparison Tab --}}
                            <div class="tab-pane fade show active" id="changes" role="tabpanel"
                                aria-labelledby="changes-tab">
                                <div class="comparison-card">
                                    <div class="comparison-header">
                                        <div class="comparison-row">
                                            <div class="comparison-cell">{{ __('admin.field') }}</div>
                                            <div class="comparison-cell">{{ __('admin.current_value') }}</div>
                                            <div class="comparison-cell">{{ __('admin.requested_value') }}</div>
                                        </div>
                                    </div>
                                    <div>
                                        @php
                                            $allFields = array_keys($requestedData);
                                            $excludedFields = ['class', 'id', 'created_at', 'updated_at', 'deleted_at', 'phone', 'country_code', 'password', 'remember_token', 'is_active', 'is_blocked', 'is_notify', 'avg_rate', 'rejection_reason', 'is_approved', 'delete_insurance_companies'];
                                            $allFields = array_diff($allFields, $excludedFields);
                                            
                                            $fieldLabels = [
                                                'trade_name' => __('admin.trade_name'),
                                                'owner_name' => __('admin.owner_name'),
                                                'name' => __('admin.trade_name'),
                                                'clinic_name' => __('admin.clinic_name'),
                                                'email' => __('admin.email'),
                                                'whatsapp' => __('admin.whatsapp'),
                                                'bank_name' => __('admin.bank_name'),
                                                'account_name' => __('admin.account_name'),
                                                'account_num' => __('admin.account_num'),
                                                'iban' => __('admin.iban'),
                                                'social_phone' => __('admin.social_phone'),
                                                'social_email' => __('admin.social_email'),
                                                'city_id' => __('admin.city'),
                                                'category_id' => __('admin.category'),
                                                'lat' => __('admin.latitude'),
                                                'lng' => __('admin.longitude'),
                                                'map_desc' => __('admin.map_description'),
                                                'address' => __('admin.address'),
                                                'image' => __('admin.image'),
                                                'license_image' => __('admin.license_image'),
                                                'owner_image' => __('admin.owner_image'),
                                                'banners' => __('admin.advertising_banners'),
                                                'commercial_register_num' => __('admin.commercial_register_num'),
                                                'start_time' => __('admin.start_time'),
                                                'end_time' => __('admin.end_time'),
                                            ];
                                        @endphp

                                        @foreach ($allFields as $field)
                                            @php
                                                $label = $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
                                                $currentValue = $currentProvider->$field ?? '';
                                                $requestedValue = $requestedData[$field] ?? '';
                                                
                                                // Check if field is an image field
                                                $isImageField = in_array($field, ['image', 'license_image', 'owner_image']);
                                                $isBannersField = $field === 'banners';
                                                
                                                // Get image paths if it's an image field
                                                $currentImagePath = '';
                                                $requestedImagePath = '';
                                                $currentBannerUrls = [];
                                                $requestedBannerUrls = [];
                                                
                                                if ($isImageField) {
                                                    // For current value, use the model's image accessor if available
                                                    if ($currentValue && method_exists($currentProvider, 'get' . ucfirst($field) . 'Attribute')) {
                                                        $currentImagePath = $currentProvider->$field;
                                                    } else {
                                                        $currentImagePath = $currentValue;
                                                    }
                                                    
                                                    // For requested value, check if it's a valid image path
                                                    if ($requestedValue) {
                                                        // Check if it's already a full URL or needs asset()
                                                        if (filter_var($requestedValue, FILTER_VALIDATE_URL) || strpos($requestedValue, 'http') === 0) {
                                                            $requestedImagePath = $requestedValue;
                                                        } elseif (strpos($requestedValue, 'storage/images/providers/') === 0 || strpos($requestedValue, '/storage/') === 0) {
                                                            $requestedImagePath = asset($requestedValue);
                                                        } else {
                                                            $requestedImagePath = asset('storage/images/providers/' . $requestedValue);
                                                        }
                                                    }
                                                    
                                                    // Also check if current value needs asset()
                                                    if ($currentImagePath && !filter_var($currentImagePath, FILTER_VALIDATE_URL) && strpos($currentImagePath, 'http') !== 0) {
                                                        if (strpos($currentImagePath, 'storage/') === 0 || strpos($currentImagePath, '/storage/') === 0) {
                                                            $currentImagePath = asset($currentImagePath);
                                                        } else {
                                                            $currentImagePath = asset('storage/' . $currentImagePath);
                                                        }
                                                    }
                                                }

                                                if ($isBannersField) {
                                                    $currentBannerUrls = $currentProvider->banner_urls ?? [];
                                                    $requestedFilenames = is_array($requestedValue)
                                                        ? array_values(array_filter($requestedValue))
                                                        : ($requestedValue ? [$requestedValue] : []);
                                                    $requestedBannerUrls = collect($requestedFilenames)->map(function ($filename) {
                                                        if (filter_var($filename, FILTER_VALIDATE_URL) || strpos($filename, 'http') === 0) {
                                                            return $filename;
                                                        }
                                                        if (strpos($filename, 'storage/images/providers/') === 0 || strpos($filename, '/storage/') === 0) {
                                                            return asset($filename);
                                                        }

                                                        return asset('storage/images/providers/' . $filename);
                                                    })->values()->all();
                                                    $currentValue = '';
                                                    $requestedValue = '';
                                                }
                                                
                                                if (in_array($field, ['start_time', 'end_time'])) {
                                                    $currentValue = $currentValue
                                                        ? \Carbon\Carbon::parse($currentValue)->format('H:i')
                                                        : '';
                                                    $requestedValue = $requestedValue
                                                        ? \Carbon\Carbon::parse($requestedValue)->format('H:i')
                                                        : '';
                                                }
                                                
                                                if (in_array($field, ['clinic_name'])) {
                                                    $currentValue = is_array($currentValue) 
                                                        ? ($currentValue[app()->getLocale()] ?? '') 
                                                        : $currentValue;
                                                    $requestedValue = is_array($requestedValue) 
                                                        ? ($requestedValue[app()->getLocale()] ?? '') 
                                                        : $requestedValue;
                                                }
                                                
                                                
                                                if ($field == 'city_id' && $currentValue) {
                                                    $currentCity = $currentProvider->city ?? null;
                                                    $currentValue = $currentCity ? ($currentCity->getTranslations('name')[app()->getLocale()] ?? $currentCity->name) : $currentValue;
                                                }
                                                if ($field == 'city_id' && $requestedValue) {
                                                    $requestedCity = \App\Models\City::find($requestedValue);
                                                    $requestedValue = $requestedCity ? ($requestedCity->getTranslations('name')[app()->getLocale()] ?? $requestedCity->name) : $requestedValue;
                                                }
                                                
                                                if ($field == 'category_id' && $currentValue) {
                                                    $currentCategory = $currentProvider->category ?? null;
                                                    $currentValue = $currentCategory ? ($currentCategory->getTranslations('name')[app()->getLocale()] ?? $currentCategory->name) : $currentValue;
                                                }
                                                if ($field == 'category_id' && $requestedValue) {
                                                    $requestedCategory = \App\Models\Category::find($requestedValue);
                                                    $requestedValue = $requestedCategory ? ($requestedCategory->getTranslations('name')[app()->getLocale()] ?? $requestedCategory->name) : $requestedValue;
                                                }
                                                
                                                if ($isBannersField) {
                                                    $hasChange = json_encode(array_values($currentProvider->banners ?? []))
                                                        !== json_encode($requestedFilenames ?? []);
                                                } else {
                                                    $hasChange = $currentValue != $requestedValue || $currentImagePath != $requestedImagePath;
                                                }
                                            @endphp
                                            <div class="comparison-row">
                                                <div class="comparison-cell field-name">
                                                    <i class="feather icon-{{ $hasChange ? 'edit-2' : 'check' }} mr-1"></i>
                                                    {{ $label }}
                                                </div>
                                                <div class="comparison-cell">
                                                    @if ($isBannersField && count($currentBannerUrls))
                                                        <div class="d-flex flex-wrap">
                                                            @foreach ($currentBannerUrls as $bannerUrl)
                                                                <div class="image-container mr-2 mb-2">
                                                                    <img src="{{ $bannerUrl }}" alt="{{ $label }}"
                                                                         class="image-preview"
                                                                         onclick="openImageModal('{{ $bannerUrl }}')">
                                                                </div>
                                                            @endforeach
                                                            <small class="text-muted w-100">{{ __('admin.current_image') }}</small>
                                                        </div>
                                                    @elseif ($isImageField && $currentImagePath)
                                                        <div class="image-container">
                                                            <img src="{{ $currentImagePath }}" alt="{{ $label }}" 
                                                                 class="image-preview" 
                                                                 onclick="openImageModal('{{ $currentImagePath }}')">
                                                            <small class="text-muted">{{ __('admin.current_image') }}</small>
                                                        </div>
                                                    @else
                                                        <span class="value-badge {{ $hasChange ? 'value-current' : 'value-unchanged' }}">
                                                            {{ $currentValue !== '' && $currentValue !== null ? $currentValue : __('admin.no_value') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="comparison-cell">
                                                    @if ($isBannersField && count($requestedBannerUrls))
                                                        <div class="d-flex flex-wrap">
                                                            @foreach ($requestedBannerUrls as $bannerUrl)
                                                                <div class="image-container mr-2 mb-2">
                                                                    <img src="{{ $bannerUrl }}" alt="{{ $label }}"
                                                                         class="image-preview"
                                                                         onclick="openImageModal('{{ $bannerUrl }}')">
                                                                </div>
                                                            @endforeach
                                                            <small class="text-muted w-100">{{ __('admin.requested_image') }}</small>
                                                        </div>
                                                    @elseif ($isImageField && $requestedImagePath)
                                                        <div class="image-container">
                                                            <img src="{{ $requestedImagePath }}" alt="{{ $label }}" 
                                                                 class="image-preview" 
                                                                 onclick="openImageModal('{{ $requestedImagePath }}')">
                                                            <small class="text-muted">{{ __('admin.requested_image') }}</small>
                                                        </div>
                                                    @else
                                                        <span class="value-badge {{ $hasChange ? 'value-requested' : 'value-unchanged' }}">
                                                            {{ $requestedValue !== '' && $requestedValue !== null ? $requestedValue : __('admin.no_value') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Relations Tab --}}
                            @if (isset($allData['relations']) && !empty($allData['relations']))
                                <div class="tab-pane fade" id="relations" role="tabpanel" aria-labelledby="relations-tab">
                                    @foreach ($allData['relations'] as $relationName => $relationData)
                                        <div class="relation-card">
                                            <div class="relation-header">
                                                <i class="feather icon-link mr-1"></i>
                                                {{ ucfirst(str_replace('_', ' ', $relationName)) }}
                                            </div>
                                            <div class="card-body">
                                                @if (is_array($relationData))
                                                    @if (isset($relationData[0]) && is_array($relationData[0]))
                                                        <div class="table-responsive">
                                                            <table class="table table-hover">
                                                                <thead class="thead-light">
                                                                    <tr>
                                                                        @foreach (array_keys($relationData[0]) as $key)
                                                                            @if (!in_array($key, ['class', 'id', 'created_at', 'updated_at']))
                                                                                <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                                            @endif
                                                                        @endforeach
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($relationData as $item)
                                                                        <tr>
                                                                            @foreach ($item as $key => $value)
                                                                                @if (!in_array($key, ['class', 'id', 'created_at', 'updated_at']))
                                                                                    <td>
                                                                                        @if (is_array($value))
                                                                                            <code>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</code>
                                                                                        @elseif(is_bool($value))
                                                                                            <span
                                                                                                class="badge {{ $value ? 'badge-success' : 'badge-secondary' }}">{{ $value ? __('admin.yes') : __('admin.no') }}</span>
                                                                                        @else
                                                                                            {{ $value ?? __('admin.no_value') }}
                                                                                        @endif
                                                                                    </td>
                                                                                @endif
                                                                            @endforeach
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless">
                                                                <tbody>
                                                                    @foreach ($relationData as $key => $value)
                                                                        @if (!in_array($key, ['class', 'id', 'created_at', 'updated_at']))
                                                                            <tr>
                                                                                <th style="width: 30%">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                                                <td>
                                                                                    @if (is_array($value))
                                                                                        <code>{{ json_encode($value, JSON_UNESCAPED_UNICODE) }}</code>
                                                                                    @elseif(is_bool($value))
                                                                                        <span
                                                                                            class="badge {{ $value ? 'badge-success' : 'badge-secondary' }}">{{ $value ? __('admin.yes') : __('admin.no') }}</span>
                                                                                    @else
                                                                                        {{ $value ?? __('admin.no_value') }}
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                @else
                                                    <p class="text-muted text-center py-3">{{ __('admin.no_data') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                @if (is_null($entityUpdate->is_approved))
                    <div class="action-buttons">
                        <div class="row">
                            <div class="col-12 text-center">
                                <button type="button" class="btn btn-success btn-lg approve-request mr-2"
                                    data-id="{{ $entityUpdate->id }}">
                                    <i class="feather icon-check-circle mr-1"></i> {{ __('admin.approve') }}
                                </button>
                                <button type="button" class="btn btn-danger btn-lg reject-request"
                                    data-id="{{ $entityUpdate->id }}">
                                    <i class="feather icon-x-circle mr-1"></i> {{ __('admin.reject') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Image Modal --}}
    <div id="imageModal" class="image-modal">
        <span class="image-modal-close" onclick="closeImageModal()">&times;</span>
        <img class="image-modal-content" id="modalImage">
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/scripts/extensions/sweet-alerts.js') }}"></script>

    <script>
        // Image Modal Functions
        function openImageModal(imageSrc) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            modal.style.display = 'flex';
            modalImg.src = imageSrc;
        }

        function closeImageModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal when clicking outside the image
        window.onclick = function(event) {
            var modal = document.getElementById('imageModal');
            if (event.target == modal) {
                closeImageModal();
            }
        }

        $(document).ready(function() {
            // Approve request
            $(document).on('click', '.approve-request', function(e) {
                e.preventDefault();
                var requestId = $(this).data('id');
                var button = $(this);

                Swal.fire({
                    title: "{{ __('admin.approve_request') }}",
                    text: "{{ __('admin.are_you_sure_approve') }}",
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('admin.approve') }}',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonText: '{{ __('admin.cancel') }}',
                    cancelButtonClass: 'btn btn-secondary ml-1',
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('admin/provider-update-requests') }}/' + requestId + '/approve',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                button.html(
                                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                                ).prop('disabled', true);
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'error',
                                    title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                button.html('<i class="feather icon-check-circle mr-1"></i> {{ __('admin.approve') }}').prop(
                                    'disabled', false);
                            }
                        });
                    }
                });
            });

            // Reject request
            $(document).on('click', '.reject-request', function(e) {
                e.preventDefault();
                var requestId = $(this).data('id');
                var button = $(this);

                Swal.fire({
                    title: "{{ __('admin.reject_request') }}",
                    text: "{{ __('admin.are_you_sure_reject') }}",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '{{ __('admin.reject') }}',
                    confirmButtonClass: 'btn btn-danger',
                    cancelButtonText: '{{ __('admin.cancel') }}',
                    cancelButtonClass: 'btn btn-secondary ml-1',
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ url('admin/provider-update-requests') }}/' + requestId + '/reject',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            beforeSend: function() {
                                button.html(
                                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                                ).prop('disabled', true);
                            },
                            success: function(response) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'success',
                                    title: response.message,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                });
                                setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    position: 'top-start',
                                    type: 'error',
                                    title: xhr.responseJSON.message || '{{ __("admin.error_occurred") }}',
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                                button.html('<i class="feather icon-x-circle mr-1"></i> {{ __('admin.reject') }}').prop(
                                    'disabled', false);
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

