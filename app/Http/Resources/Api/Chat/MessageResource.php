<?php

namespace App\Http\Resources\Api\Chat;

use App\Models\Client;
use App\Models\Provider;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request): array
    {
        $message = $this->originalMessage;
        $sender  = $message?->senderable;

        return [
            'id'         => $this->id,
            'message_id' => $this->message_id,
            'body'       => $message?->body,
            'type'       => $message?->type,
            'duration'   => $message?->duration,
            'is_sender'  => (bool) $this->is_sender,
            'is_seen'    => (bool) $this->is_seen,
            'sender'     => $sender ? [
                'id'    => $sender->id,
                'type'  => $sender instanceof Provider ? 'provider' : ($sender instanceof Client ? 'client' : class_basename($sender)),
                'name'  => $sender->name ?? null,
                'image' => $sender->image ?? null,
            ] : null,
            'created_at' => $message?->created_at?->format('Y-m-d H:i'),
        ];
    }
}
