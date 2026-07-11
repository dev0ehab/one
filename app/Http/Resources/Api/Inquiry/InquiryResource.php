<?php

namespace App\Http\Resources\Api\Inquiry;

use App\Http\Resources\Api\Settings\CategoryResource;
use App\Models\Provider;
use App\Services\Chat\ChatService;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    public function toArray($request): array
    {
        $myRecipient = null;
        if (auth('sanctum')->check() && auth()->user() instanceof Provider) {
            $myRecipient = $this->recipients->firstWhere('provider_id', auth()->id());
        }

        return [
            'id'                  => $this->id,
            'inquiry_number'      => $this->inquiry_number,
            'title'               => $this->title,
            'description'         => $this->description,
            'target_type'         => $this->target_type,
            'status'              => $this->status,
            'status_label'        => __('apis.inquiry_status_' . $this->status),
            'category'            => CategoryResource::make($this->whenLoaded('category')),
            'location'            => [
                'lat'     => $this->lat ? (float) $this->lat : null,
                'lng'     => $this->lng ? (float) $this->lng : null,
                'address' => $this->address,
            ],
            'images'              => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($img) => $img->image)->values();
            }, []),
            'recipients_count'    => $this->whenLoaded('recipients', fn () => $this->recipients->count()),
            'my_recipient_status' => $myRecipient?->status,
            'reject_reason'       => $myRecipient?->reject_reason,
            'accepted_provider'   => $this->whenLoaded('acceptedProvider', function () {
                if (!$this->acceptedProvider) {
                    return null;
                }

                return [
                    'id'          => $this->acceptedProvider->id,
                    'name'        => $this->acceptedProvider->trade_name,
                    'image'       => $this->acceptedProvider->image,
                    'phone'       => $this->acceptedProvider->phone,
                ];
            }),
            'client'              => $this->whenLoaded('client', function () {
                if (!$this->client) {
                    return null;
                }

                return [
                    'id'    => $this->client->id,
                    'name'  => $this->client->name,
                    'image' => $this->client->image,
                ];
            }),
            'room_id'             => $this->resolveSharedRoomId(),
            'created_at'          => $this->created_at?->format('Y-m-d H:i'),
            'accepted_at'         => $this->accepted_at?->format('Y-m-d H:i'),
        ];
    }

    protected function resolveSharedRoomId(): ?int
    {
        if (array_key_exists('shared_room_id', $this->resource->getAttributes())) {
            return $this->shared_room_id !== null ? (int) $this->shared_room_id : null;
        }

        if (!auth('sanctum')->check() || !(auth()->user() instanceof Provider) || !$this->client) {
            return null;
        }

        return app(ChatService::class)->findPrivateRoomId(auth()->user(), $this->client);
    }
}
