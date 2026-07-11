<?php

namespace App\Http\Resources\Api\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'type'       => $this->data['type'],
            'title'      => $this->title,
            'body'       => $this->body,
            'data'       => array_merge(
                $this->data,
                [
                    'model_id' => (string) $this->data['model_id'],
                ]
            ),
            'created_at' => $this->formatCreatedAt(),
        ];
    }

    /**
     * Figma notification card: 16/01/2026 - 12:00مساء (node 1421:11232)
     */
    protected function formatCreatedAt(): string
    {
        if (!$this->created_at) {
            return '';
        }

        if (lang() === 'ar') {
            $period = $this->created_at->format('A') === 'PM' ? 'مساء' : 'صباحاً';

            return $this->created_at->format('d/m/Y') . ' - ' . $this->created_at->format('g:i') . $period;
        }

        return $this->created_at->format('d/m/Y - h:i A');
    }
}
