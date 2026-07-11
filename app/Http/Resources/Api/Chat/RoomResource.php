<?php

namespace App\Http\Resources\Api\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray($request): array
    {
        $lastMessage = $this->lastOriginalMessage;

        return [
            'id'            => $this->id,
            'type'          => $this->type,
            'other_member'  => $this->other_member,
            'unread_count'  => (int) ($this->unread_count ?? 0),
            'last_message'  => $lastMessage ? [
                'id'         => $lastMessage->id,
                'body'       => $lastMessage->body,
                'type'       => $lastMessage->type,
                'created_at' => $lastMessage->created_at?->format('Y-m-d H:i'),
            ] : null,
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
