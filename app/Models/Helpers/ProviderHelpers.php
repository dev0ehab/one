<?php

namespace App\Models\Helpers;

use App\Http\Resources\ProviderResource;
use App\Models\Admin;
use App\Models\Room;

trait ProviderHelpers
{
    /**
     * Get the resource for user.
     *
     * @return ProviderResource
     */
    public function getResource()
    {
        return new ProviderResource($this);
    }

    /**
     * Create a private admin chat room when a provider is created.
     */
    protected static function bootProviderHelpers(): void
    {
        static::created(function ($model) {
            $model->ensureAdminChatRoom();
        });
    }

    /**
     * Ensure the provider has a chat room with all admins.
     */
    public function ensureAdminChatRoom(): Room
    {
        $room = $this->chat;

        if (!$room) {
            $room = $this->room()->create([
                'order_id' => $this->id,
                'type'     => 'admin',
                'private'  => true,
            ]);
        }

        $room->members()->firstOrCreate([
            'memberable_id'   => $this->id,
            'memberable_type' => static::class,
        ]);

        foreach (Admin::all() as $admin) {
            $room->members()->firstOrCreate([
                'memberable_id'   => $admin->id,
                'memberable_type' => Admin::class,
            ]);
        }

        return $room;
    }
}
