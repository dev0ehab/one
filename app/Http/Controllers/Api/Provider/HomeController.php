<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\InquiryProvider;
use App\Models\MessageNotification;
use App\Models\Provider;
use App\Models\ProviderAd;
use App\Models\RoomMember;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    use ResponseTrait;

    /**
     * Provider home dashboard — five summary cards from design/provider/home.png:
     * admin messages, bookings, inquiries, ads, messages.
     */
    public function index(): JsonResponse
    {
        $providerId = auth()->id();
        $providerType = Provider::class;

        $providerRoomIds = RoomMember::query()
            ->where('memberable_id', $providerId)
            ->where('memberable_type', $providerType)
            ->pluck('room_id');

        $adminRoomIds = RoomMember::query()
            ->whereIn('room_id', $providerRoomIds)
            ->where('memberable_type', Admin::class)
            ->pluck('room_id');

        return $this->successData([
            'admin_messages_count' => $this->unreadMessagesCount($providerId, $providerType, $adminRoomIds),
            'bookings_count'       => Booking::query()->where('provider_id', $providerId)->count(),
            'inquiries_count'      => InquiryProvider::query()->where('provider_id', $providerId)->count(),
            'ads_count'            => ProviderAd::query()->where('provider_id', $providerId)->count(),
            'messages_count'       => $this->unreadMessagesCount(
                $providerId,
                $providerType,
                $providerRoomIds->diff($adminRoomIds)->values()
            ),
        ]);
    }

    protected function unreadMessagesCount(int $providerId, string $providerType, Collection $roomIds): int
    {
        if ($roomIds->isEmpty()) {
            return 0;
        }

        return MessageNotification::query()
            ->where('userable_id', $providerId)
            ->where('userable_type', $providerType)
            ->where('is_seen', false)
            ->where('is_sender', false)
            ->whereIn('room_id', $roomIds)
            ->count();
    }
}
