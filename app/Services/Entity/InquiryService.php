<?php

namespace App\Services\Entity;

use App\Enums\InquiryRecipientStatusEnum;
use App\Enums\InquiryStatusEnum;
use App\Enums\InquiryTargetTypeEnum;
use App\Enums\NotificationTypeEnum;
use App\Models\Client;
use App\Models\Inquiry;
use App\Services\Chat\ChatService;
use App\Services\Notification\UserNotifier;
use App\Http\Filters\InquiryFilter;
use App\Http\Resources\Api\Inquiry\InquiriesCollection;
use App\Http\Resources\Api\Inquiry\InquiryResource;
use App\Models\InquiryImage;
use App\Models\InquiryProvider;
use App\Models\Provider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InquiryService extends BaseService
{
    public function __construct(Inquiry $inquiry, InquiryFilter $filter)
    {
        parent::__construct($inquiry);
        $this->filter = $filter;
    }

    protected function baseQuery()
    {
        return $this->model::filter($this->filter)
            ->with(['category', 'images', 'client', 'acceptedProvider', 'recipients.provider']);
    }

    public function indexApiForClient()
    {
        request()->merge([
            'mine' => true,
            'tab'  => request('tab', 'pending'),
        ]);

        $paginated = $this->baseQuery()
            ->latest()
            ->paginate(request()->paginate ?? 10);

        return new InquiriesCollection($paginated);
    }

    public function indexApiForProvider()
    {
        request()->merge([
            'provider' => true,
            'tab'      => request('tab', 'pending'),
        ]);

        $paginated = $this->baseQuery()
            ->latest()
            ->paginate(request()->paginate ?? 10);

        $this->attachSharedRoomIds($paginated->getCollection());

        return new InquiriesCollection($paginated);
    }

    public function showApiForClient(int $id): InquiryResource
    {
        $inquiry = $this->findClientInquiry($id);

        return InquiryResource::make($inquiry);
    }

    public function showApiForProvider(int $id): InquiryResource
    {
        $inquiry = $this->findProviderInquiry($id);
        $this->attachSharedRoomIds(collect([$inquiry]));

        return InquiryResource::make($inquiry);
    }

    public function store($request): array
    {
        DB::beginTransaction();

        try {
            $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

            $providers = $this->resolveTargetProviders(
                (int) $data['category_id'],
                $data['target_type'],
                isset($data['lat']) ? (float) $data['lat'] : null,
                isset($data['lng']) ? (float) $data['lng'] : null
            );

            if ($providers->isEmpty()) {
                return ['status' => 'fail', 'message' => __('apis.inquiry_no_providers')];
            }

            $inquiry = $this->model::create([
                'client_id'    => auth()->id(),
                'category_id'  => $data['category_id'],
                'title'        => $data['title'],
                'description'  => $data['description'],
                'target_type'  => $data['target_type'],
                'status'       => InquiryStatusEnum::PENDING,
                'lat'          => $data['lat'] ?? null,
                'lng'          => $data['lng'] ?? null,
                'address'      => $data['address'] ?? null,
            ]);

            foreach ($providers as $provider) {
                InquiryProvider::create([
                    'inquiry_id'  => $inquiry->id,
                    'provider_id' => $provider->id,
                    'status'      => InquiryRecipientStatusEnum::PENDING,
                ]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    InquiryImage::create([
                        'inquiry_id' => $inquiry->id,
                        'image'      => $image,
                    ]);
                }
            }

            DB::commit();

            UserNotifier::send(
                $providers,
                NotificationTypeEnum::InquiryReceived,
                $inquiry,
                $inquiry->id,
                ['title' => $inquiry->title]
            );

            return [
                'status'  => 'success',
                'message' => __('apis.inquiry_created'),
                'data'    => InquiryResource::make($inquiry->load(['category', 'images', 'recipients.provider'])),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancel(int $id): array
    {
        $inquiry = $this->findClientInquiry($id);

        if ($inquiry->status !== InquiryStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.inquiry_invalid_action')];
        }

        $inquiry->update(['status' => InquiryStatusEnum::CANCELLED]);

        $inquiry->recipients()
            ->where('status', InquiryRecipientStatusEnum::PENDING)
            ->update([
                'status'       => InquiryRecipientStatusEnum::REJECTED,
                'responded_at' => now(),
            ]);

        return [
            'status'  => 'success',
            'message' => __('apis.inquiry_cancelled'),
            'data'    => InquiryResource::make($inquiry->fresh(['category', 'images', 'recipients.provider'])),
        ];
    }

    public function providerAccept(int $id): array
    {
        $inquiry = $this->findProviderInquiry($id);
        $recipient = $this->findProviderRecipient($inquiry);

        if ($inquiry->status !== InquiryStatusEnum::PENDING
            || $recipient->status !== InquiryRecipientStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.inquiry_invalid_action')];
        }

        DB::beginTransaction();

        try {
            $recipient->update([
                'status'       => InquiryRecipientStatusEnum::ACCEPTED,
                'responded_at' => now(),
            ]);

            $inquiry->update([
                'status'               => InquiryStatusEnum::ACCEPTED,
                'accepted_provider_id' => auth()->id(),
                'accepted_at'          => now(),
            ]);

            $inquiry->recipients()
                ->where('id', '!=', $recipient->id)
                ->where('status', InquiryRecipientStatusEnum::PENDING)
                ->update([
                    'status'       => InquiryRecipientStatusEnum::REJECTED,
                    'responded_at' => now(),
                ]);

            DB::commit();

            $inquiry->load('client');
            UserNotifier::send(
                $inquiry->client,
                NotificationTypeEnum::InquiryAccepted,
                $inquiry,
                $inquiry->id,
                ['title' => $inquiry->title]
            );

            return [
                'status'  => 'success',
                'message' => __('apis.inquiry_accepted'),
                'data'    => InquiryResource::make($inquiry->fresh(['category', 'images', 'recipients.provider', 'acceptedProvider'])),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function providerReject(int $id, ?string $reason = null): array
    {
        $inquiry = $this->findProviderInquiry($id);
        $recipient = $this->findProviderRecipient($inquiry);

        if ($inquiry->status !== InquiryStatusEnum::PENDING
            || $recipient->status !== InquiryRecipientStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.inquiry_invalid_action')];
        }

        DB::beginTransaction();

        try {
            $recipient->update([
                'status'        => InquiryRecipientStatusEnum::REJECTED,
                'reject_reason' => $reason,
                'responded_at'  => now(),
            ]);

            $hasPending = $inquiry->recipients()
                ->where('status', InquiryRecipientStatusEnum::PENDING)
                ->exists();

            $fullyRejected = false;

            if (!$hasPending) {
                $inquiry->update(['status' => InquiryStatusEnum::REJECTED]);
                $fullyRejected = true;
            }

            DB::commit();

            if ($fullyRejected) {
                $inquiry->load('client');
                UserNotifier::send(
                    $inquiry->client,
                    NotificationTypeEnum::InquiryRejected,
                    $inquiry,
                    $inquiry->id,
                    ['title' => $inquiry->title]
                );
            }

            return [
                'status'  => 'success',
                'message' => __('apis.inquiry_rejected'),
                'data'    => InquiryResource::make($inquiry->fresh(['category', 'images', 'recipients.provider'])),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function resolveTargetProviders(
        int $categoryId,
        string $targetType,
        ?float $lat,
        ?float $lng
    ): Collection {
        $query = Provider::query()
            ->where('category_id', $categoryId)
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->where('is_approved', 1)
            ->whereNotNull('lat')
            ->whereNotNull('lng');

        if ($targetType === InquiryTargetTypeEnum::NEAREST) {
            if ($lat === null || $lng === null) {
                return collect();
            }

            $radius = (float) (settings()['inquiry_radius_km'] ?? 20);

            $provider = (clone $query)
                ->selectRaw(
                    '*, (6371 * acos(
                    cos(radians(?))
                    * cos(radians(CAST(lat AS DECIMAL(10,7))))
                    * cos(radians(CAST(lng AS DECIMAL(10,7))) - radians(?))
                    + sin(radians(?))
                    * sin(radians(CAST(lat AS DECIMAL(10,7))))
                )) AS distance',
                    [$lat, $lng, $lat]
                )
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->first();

            return $provider ? collect([$provider]) : collect();
        }

        return $query->get();
    }

    protected function findClientInquiry(int $id): Inquiry
    {
        return $this->model::with(['category', 'images', 'acceptedProvider', 'recipients.provider'])
            ->where('client_id', auth()->id())
            ->findOrFail($id);
    }

    protected function findProviderInquiry(int $id): Inquiry
    {
        return $this->model::with(['category', 'images', 'client', 'recipients.provider'])
            ->whereHas('recipients', fn ($q) => $q->where('provider_id', auth()->id()))
            ->findOrFail($id);
    }

    protected function findProviderRecipient(Inquiry $inquiry): InquiryProvider
    {
        return $inquiry->recipients()
            ->where('provider_id', auth()->id())
            ->firstOrFail();
    }

    protected function attachSharedRoomIds(Collection $inquiries): void
    {
        $provider = auth()->user();

        if (!$provider instanceof Provider) {
            return;
        }

        $clientIds = $inquiries->pluck('client_id')->filter()->unique()->values()->all();
        $roomIdsByClientId = app(ChatService::class)->findPrivateRoomIdsBetween(
            $provider,
            Client::class,
            $clientIds
        );

        $inquiries->each(function (Inquiry $inquiry) use ($roomIdsByClientId) {
            $inquiry->shared_room_id = $roomIdsByClientId[$inquiry->client_id] ?? null;
        });
    }
}
