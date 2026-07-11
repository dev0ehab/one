<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatRoomResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'type'         => $this->type,
            'other_member' => $this->other_member,
            'unread_count' => (int) ($this->unread_count ?? 0),
            'members'      => $this->whenLoaded('members', function () {
                return $this->members
                    ->filter(fn ($member) => $member->memberable)
                    ->map(function ($member) {
                        $memberable = $member->memberable;

                        return [
                            'id'    => $memberable->id,
                            'type'  => class_basename($memberable),
                            'name'  => $memberable->name ?? null,
                            'image' => $memberable->image ?? null,
                        ];
                    })
                    ->values();
            }, []),
        ];
    }
}
