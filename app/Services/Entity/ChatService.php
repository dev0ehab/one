<?php

namespace App\Services\Entity;

use App\Http\Resources\Api\Chat\ChatRoomResource;
use App\Http\Resources\Api\Chat\MessagesCollection;
use App\Http\Resources\Api\Chat\RoomsCollection;
use App\Models\Client;
use App\Models\Provider;
use App\Models\Room;
use App\Models\RoomMember;
use App\Services\Chat\ChatService as CoreChatService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ChatService
{
    public function __construct(protected CoreChatService $chatService)
    {
    }

    public function indexApi(): RoomsCollection
    {
        $user = auth()->user();

        $rooms = Room::query()
            ->whereIn('id', $this->userRoomIds($user))
            ->where('last_message_id', '!=', 0)
            ->with([
                'members.memberable',
                'lastOriginalMessage.senderable',
            ])
            ->orderByDesc('last_message_id')
            ->paginate(request()->paginate ?? 15);

        $rooms->getCollection()->transform(function (Room $room) use ($user) {
            $room->other_member = $this->resolveOtherMember($room, $user);
            $room->unread_count = $this->chatService->getRoomUnseenMessagesCount($room, $user);

            return $room;
        });

        return new RoomsCollection($rooms);
    }

    public function startApi(int $otherId): array
    {
        $user = auth()->user();

        if ($user instanceof Client) {
            $other = Provider::findOrFail($otherId);
        } elseif ($user instanceof Provider) {
            $other = Client::findOrFail($otherId);
        } else {
            abort(403);
        }

        $room = $this->chatService->createPrivateRoom($user, 'direct', $other);

        $room->load(['members.memberable', 'lastOriginalMessage.senderable']);
        $room->other_member = $this->formatMember($other);
        $room->unread_count = 0;

        $messages = $this->chatService->getRoomMessages($room, $user);

        return [
            'room'     => ChatRoomResource::make($room),
            'messages' => new MessagesCollection($messages),
        ];
    }

    public function showApi(int $id): array
    {
        $user = auth()->user();
        $room = $this->findUserRoom($user, $id);

        $this->chatService->seeRoomMessages($room, $user);

        $room->other_member = $this->resolveOtherMember($room, $user);
        $room->unread_count = 0;

        $messages = $this->chatService->getRoomMessages($room, $user);

        return [
            'room'     => ChatRoomResource::make($room),
            'messages' => new MessagesCollection($messages),
        ];
    }

    protected function userRoomIds(Model $user): Collection
    {
        return RoomMember::query()
            ->where('memberable_id', $user->id)
            ->where('memberable_type', get_class($user))
            ->pluck('room_id');
    }

    protected function findUserRoom(Model $user, int $roomId): Room
    {
        $isMember = RoomMember::query()
            ->where('room_id', $roomId)
            ->where('memberable_id', $user->id)
            ->where('memberable_type', get_class($user))
            ->exists();

        abort_if(!$isMember, 404, __('apis.chat_room_not_found'));

        return Room::query()
            ->with(['members.memberable', 'lastOriginalMessage.senderable'])
            ->findOrFail($roomId);
    }

    protected function resolveOtherMember(Room $room, Model $user): ?array
    {
        $member = $room->members
            ->first(function ($roomMember) use ($user) {
                $memberable = $roomMember->memberable;

                if (!$memberable) {
                    return false;
                }

                return get_class($memberable) !== get_class($user)
                    || $memberable->id !== $user->id;
            });

        if (!$member?->memberable) {
            return null;
        }

        return $this->formatMember($member->memberable);
    }

    protected function formatMember(Model $memberable): array
    {
        if ($memberable instanceof Provider) {
            return [
                'id'    => $memberable->id,
                'type'  => 'provider',
                'name'  => $memberable->trade_name,
                'image' => $memberable->image,
            ];
        }

        if ($memberable instanceof Client) {
            return [
                'id'    => $memberable->id,
                'type'  => 'client',
                'name'  => $memberable->name,
                'image' => $memberable->image,
            ];
        }

        return [
            'id'    => $memberable->id,
            'type'  => class_basename($memberable),
            'name'  => $memberable->name ?? null,
            'image' => $memberable->image ?? null,
        ];
    }
}
