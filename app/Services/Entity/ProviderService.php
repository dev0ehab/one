<?php

namespace App\Services\Entity;

use App\Http\Filters\ProviderFilter;
use App\Http\Resources\Api\Client\Providers\ProviderAdsCollection;
use App\Http\Resources\Api\Client\Providers\ProviderMapResource;
use App\Http\Resources\Api\Client\Providers\ProviderReviewsCollection;
use App\Mail\SendMail;
use App\Models\Category;
use App\Enums\CategoryTypeEnum;
use App\Models\Provider;
use App\Models\ProviderAd;
use App\Models\Rate;
use App\Notifications\BlockUser;
use App\Notifications\NotifyUser;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ProviderService extends BaseService
{
    use ResponseTrait;

    public function __construct(Provider $provider, ProviderFilter $filter)
    {
        $this->model = $provider;
        $this->filter = $filter;
    }

    protected function clientProvidersQuery(bool $requireLocation = false, bool $forList = false)
    {
        return $this->model::filter($this->filter)
            ->when(
                $forList,
                fn ($q) => $q->with(['workDays']),
                fn ($q) => $q->with(['city.country', 'category.parent', 'workDays'])
            )
            ->withCount('rates')
            ->when(auth('sanctum')->check(), fn ($q) => $q->withIsFavorite()->withHasActiveBooking())
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->where('is_approved', 1)
            ->when($requireLocation, fn ($q) => $q->whereNotNull('lat')->whereNotNull('lng'));
    }

    protected function findClientProvider(int|string $id): Provider
    {
        return $this->model::where('is_active', true)
            ->where('is_blocked', false)
            ->where('is_approved', 1)
            ->findOrFail($id);
    }

    public function indexApi()
    {
        $query = $this->clientProvidersQuery(forList: true)->latest('id');

        $data = $query
            ->when(request()->has('is_paginated') && !request()->is_paginated, fn ($q) => $q->get())
            ->when(!request()->has('is_paginated') || request()->is_paginated, fn ($q) => $q->paginate(request()->paginate ?? 10));

        return $this->apiCollectionResponse($data);
    }

    public function mapApi(): array
    {
        if (request()->filled(['lat', 'lng']) && !request()->has('sort') && !request()->has('nearby')) {
            request()->merge(['sort' => 'nearest']);
        }

        $providers = $this->clientProvidersQuery(requireLocation: true)->get();

        return [
            'providers' => ProviderMapResource::collection($providers),
        ];
    }

    public function showApi($id)
    {
        $data = $this->model::with([
            'city.country',
            'category.parent',
            'workDays',
        ])
            ->withCount('rates')
            ->when(auth('sanctum')->check(), fn ($q) => $q->withIsFavorite()->withHasActiveBooking())
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->where('is_approved', 1)
            ->findOrFail($id);

        return $this->apiResourceResponse($data);
    }

    public function reviewsApi($id)
    {
        $provider = $this->findClientProvider($id);

        $reviews = Rate::where('provider_id', $provider->id)
            ->with('client:id,name,image')
            ->latest('id')
            ->paginate(request('paginate', 10));

        return ProviderReviewsCollection::make($reviews);
    }

    public function adsApi($id)
    {
        $provider = $this->findClientProvider($id);

        $perPage = (int) request('paginate', 10);
        $page = max(1, (int) request('page', 1));

        $ads = ProviderAd::where('provider_id', $provider->id)
            ->active()
            ->with('images')
            ->latest('approved_at')
            ->paginate($perPage, ['*'], 'page', $page);

        app(AdService::class)->recordAdViewsForPage(
            $page,
            $perPage,
            fn ($query) => $query->where('provider_id', $provider->id)
        );

        return ProviderAdsCollection::make($ads);
    }

    public function getCategories()
    {
        $query = Category::where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->latest();

        if (request()->boolean('is_paginated')) {
            return $query->paginate(request('paginate', 15));
        }

        return $query->get();
    }

    // --- Admin / dashboard methods below (unchanged signatures) ---

    public function index()
    {
        if (request()->ajax()) {
            $providers = $this->model::filter($this->filter)
                ->with(['city.country', 'category.parent', 'workDays', 'chat'])
                ->search(request()->searchArray)
                ->latest()
                ->paginate(30);

            $tableViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.table')
                ? 'admin.' . $this->model->getFolderName() . '.table'
                : 'admin.' . $this->model->camelModelName() . '.table';

            $html = view($tableViewPath, compact('providers'))->render();

            return response()->json(['html' => $html]);
        }

        $indexViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.index')
            ? 'admin.' . $this->model->getFolderName() . '.index'
            : 'admin.' . $this->model->camelModelName() . '.index';

        return view($indexViewPath);
    }

    public function store($request)
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        $provider = $this->model::create(collect($data)->except(['work_days'])->toArray());

        if (isset($data['work_days']) && is_array($data['work_days'])) {
            foreach ($data['work_days'] as $workDayData) {
                $workDayData['day'] = $workDayData['day'] ?? '';
                $workDayData['start_at'] = $workDayData['start_at'] ?? '';
                $workDayData['end_at'] = $workDayData['end_at'] ?? '';
                $workDayData['name'] = [
                    'ar' => $workDayData['name']['ar'] ?? '',
                    'en' => $workDayData['name']['en'] ?? '',
                ];
                $provider->workDays()->create($workDayData);
            }
        }

        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($provider->fresh());
        }

        \App\Traits\ReportTrait::addToLog('add_' . $this->model->lowerModelName());

        return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
    }

    public function edit($id)
    {
        $provider = Provider::with(['city.country', 'category.parent', 'workDays'])->findOrFail($id);

        $viewPath = view()->exists('admin.' . $this->model->getFolderName() . '.edit')
            ? 'admin.' . $this->model->getFolderName() . '.edit'
            : 'admin.' . $this->model->camelModelName() . '.edit';

        return view($viewPath, ['provider' => $provider]);
    }

    public function show($id)
    {
        $row = Provider::with([
            'city.country',
            'category.parent',
            'workDays',
        ])->findOrFail($id);

        if (request()->ajax()) {
            if (request()->type == 'main_data') {
                $html = view('admin.providers.parts.main_data', compact('row'))->render();
            }

            if (request()->type == 'contacts') {
                $contacts = collect([]);
                $html = view('admin.providers.parts.contacts', compact('contacts'))->render();
            }

            if (request()->type == 'dashboard') {
                $html = view('admin.providers.parts.dashboard', compact('row'))->render();
            }

            if (request()->type == 'work_days') {
                $html = view('admin.providers.parts.work_days', compact('row'))->render();
            }

            if (request()->type == 'ads') {
                $ads = ProviderAd::where('provider_id', $row->id)
                    ->with('images')
                    ->latest()
                    ->paginate(10)
                    ->appends(['type' => 'ads']);
                $html = view('admin.providers.parts.ads', compact('row', 'ads'))->render();
            }

            return response()->json(['html' => $html ?? '']);
        }

        return view('admin.providers.show', ['row' => $row]);
    }

    public function block($request)
    {
        $provider = Provider::findOrFail($request->id);
        $provider->update(['is_blocked' => !$provider->is_blocked]);

        if ($provider->refresh()->is_blocked) {
            Notification::send($provider, new BlockUser);
        }

        return response()->json(['message' => $provider->refresh()->is_blocked ? __('admin.user_blocked') : __('admin.user_unblocked')]);
    }

    public function notify($request)
    {
        if ($request->type == 'notifications' || $request->type == 'notify') {
            $provider = ('all' == $request->id) ? Provider::latest()->get() : Provider::findOrFail($request->id);
            Notification::send($provider, new NotifyUser($request->all()));
        } else {
            try {
                $mail = ('all' == $request->id) ? Provider::where('email', '!=', null)->get()->pluck('email')->toArray() : Provider::findOrFail($request->id)->email;
                Mail::to($mail)->send(new SendMail(['title' => 'اشعار اداري', 'message' => $request->message]));
            } catch (\Exception $e) {
                return response()->json(['msg' => $e->getMessage()])->setStatusCode(500);
            }
        }

        return true;
    }

    public function storeWorkDay($request, $providerId)
    {
        $provider = Provider::findOrFail($providerId);
        $validatedData = $request->validated();

        $workDayData = [
            'day'      => $validatedData['day'],
            'start_at' => $validatedData['start_at'],
            'end_at'   => $validatedData['end_at'],
            'name'     => [
                'ar' => $validatedData['name_ar'] ?? '',
                'en' => $validatedData['name_en'] ?? '',
            ],
        ];

        $provider->workDays()->create($workDayData);

        return response()->json([
            'success' => true,
            'message' => 'admin.success',
        ]);
    }

    public function update($id, $request)
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

        $provider = $this->model::findOrFail($id);
        $provider->update(collect($data)->except(['work_days'])->toArray());

        if (isset($data['work_days']) && is_array($data['work_days'])) {
            $provider->workDays()->delete();
            foreach ($data['work_days'] as $workDayData) {
                $workDayData['day'] = $workDayData['day'] ?? '';
                $workDayData['start_at'] = $workDayData['start_at'] ?? '';
                $workDayData['end_at'] = $workDayData['end_at'] ?? '';
                $workDayData['name'] = [
                    'ar' => $workDayData['name']['ar'] ?? '',
                    'en' => $workDayData['name']['en'] ?? '',
                ];
                $provider->workDays()->create($workDayData);
            }
        }

        if ($this->isApiRequest()) {
            return $this->apiResourceResponse($provider->fresh());
        }

        \App\Traits\ReportTrait::addToLog('edit_' . $this->model->lowerModelName());

        return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
    }

    public function accept($request)
    {
        $id = is_object($request) ? ($request->id ?? $request->input('id')) : ($request['id'] ?? null);
        $provider = Provider::findOrFail($id);

        if (!is_null($provider->is_approved)) {
            return response()->json([
                'message' => __('admin.provider_not_pending'),
            ], 400);
        }

        $provider->update([
            'is_approved'      => 1,
            'rejection_reason' => null,
        ]);

        return response()->json([
            'message' => __('admin.provider_accepted_successfully'),
        ]);
    }

    public function reject($request)
    {
        $id = is_object($request) ? ($request->id ?? $request->input('id')) : ($request['id'] ?? null);
        $provider = Provider::findOrFail($id);

        if (!is_null($provider->is_approved)) {
            return response()->json([
                'message' => __('admin.provider_not_pending'),
            ], 400);
        }

        $reason = is_object($request) ? ($request->reason ?? $request->input('reason')) : ($request['reason'] ?? null);

        if (empty($reason)) {
            return response()->json([
                'message' => __('admin.rejection_reason_required'),
            ], 422);
        }

        $provider->update([
            'is_approved'      => 0,
            'rejection_reason' => $reason,
        ]);

        return response()->json([
            'message' => __('admin.provider_rejected_successfully'),
        ]);
    }
}
