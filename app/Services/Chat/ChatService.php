<?php
namespace App\Services\Chat;

use App\Models\Admin;
use App\Models\Message;
use App\Models\Room;
use App\Models\MessageNotification;
use App\Traits\UploadTrait;
use DB;

class ChatService
{
  use UploadTrait;

  public function createRoom($creator, $type, $order_id = null)
  {
    $newRoom = $creator->ownRooms()->firstOrCreate([
      'type' => $type,
      'order_id' => $order_id,
    ]);

    $this->joinRoom($newRoom, $creator);
    $this->joinRoom($newRoom, Admin::first());

    return $newRoom;
  }

  public function findPrivateRoomId($memberA, $memberB): ?int
  {
    $myRooms = DB::table('rooms')
      ->join('room_members', 'room_members.room_id', 'rooms.id')
      ->where('room_members.memberable_id', $memberA->id)
      ->where('room_members.memberable_type', get_class($memberA))
      ->where('rooms.private', 1)
      ->pluck('rooms.id')
      ->toArray();

    if (empty($myRooms)) {
      return null;
    }

    $oldRoom = DB::table('rooms')
      ->join('room_members', 'room_members.room_id', 'rooms.id')
      ->where('room_members.memberable_id', $memberB->id)
      ->where('room_members.memberable_type', get_class($memberB))
      ->whereIn('rooms.id', $myRooms)
      ->select('rooms.id')
      ->first();

    return $oldRoom?->id ? (int) $oldRoom->id : null;
  }

  /**
   * @param  array<int>  $otherIds
   * @return array<int, int> other member id => room id
   */
  public function findPrivateRoomIdsBetween($member, string $otherType, array $otherIds): array
  {
    $otherIds = array_values(array_unique(array_filter($otherIds)));

    if (empty($otherIds)) {
      return [];
    }

    $myRooms = DB::table('rooms')
      ->join('room_members', 'room_members.room_id', 'rooms.id')
      ->where('room_members.memberable_id', $member->id)
      ->where('room_members.memberable_type', get_class($member))
      ->where('rooms.private', 1)
      ->pluck('rooms.id')
      ->toArray();

    if (empty($myRooms)) {
      return [];
    }

    $rows = DB::table('room_members')
      ->whereIn('room_id', $myRooms)
      ->where('memberable_type', $otherType)
      ->whereIn('memberable_id', $otherIds)
      ->select('memberable_id', 'room_id')
      ->get();

    $map = [];
    foreach ($rows as $row) {
      if (!isset($map[$row->memberable_id])) {
        $map[(int) $row->memberable_id] = (int) $row->room_id;
      }
    }

    return $map;
  }

  public function createPrivateRoom($creator, $type, $memberable)
  {
    $roomId = $this->findPrivateRoomId($creator, $memberable);

    if ($roomId) {
      return Room::find($roomId);
    }

    $room = $creator->ownRooms()->create([
      'type' => $type,
      'private' => 1
    ]);

    $this->joinRoom($room, $creator);
    $this->joinRoom($room, $memberable);

    return $room;
  }

  public function joinRoom($room, $member)
  {
    /** join room in room_members table */
    $member->rooms()->firstOrCreate(['room_id' => $room->id]);
  }

  public function leaveRoom($room, $member)
  {
    $member->rooms()->where(['room_id' => $room->id])->delete();
  }

  public function getRoomMembers($room)
  {
    return $room->members->pluck('memberable');
  }

  public function getOtherRoomMembers($room, $user)
  {
    $members = $room->members
      ->filter(function ($q) use ($user) {
        if ($q->memberable_type != get_class($user) || $q->memberable_id != $user->id) {
          return $q;
        }
      })->flatten();
    return $members;
  }

  public function getRoomMessages($room, $userable)
  {
    $roomMessagesQuery = $room->messages()
      ->with('originalMessage.senderable')
      ->where('userable_id', $userable->id)
      ->where('userable_type', get_class($userable))
      ->orderBy('created_at', 'desc')
      ->paginate($this->chatPaginateNum());

    return $roomMessagesQuery;
  }

  public function getRoomMessagesResource($room, $userable)
  {
    $roomMessagesQuery = $room->messages()
      ->with('originalMessage.senderable')
      ->where('userable_id', $userable->id)
      ->where('userable_type', get_class($userable))
      ->orderBy('created_at', 'desc')
      ->get();

    return $roomMessagesQuery;
  }

  public function getUserRooms($userable)
  {
    $roomIds = $userable->joinedRooms()->pluck('room_id');

    $rooms = Room::query()
      ->whereIn('id', $roomIds)
      ->where('last_message_id', '!=', 0)
      ->with(['members.memberable', 'lastOriginalMessage.senderable'])
      ->orderByDesc('last_message_id')
      ->get();

    $rooms->each(function (Room $room) use ($userable) {
      $room->loadLastAuthMessage($userable);
    });

    return $rooms;
  }

  public function chatPaginateNum()
  {
    return request()->paginate ?? 100;
  }

  public function getRoomUnseenMessagesCount($room, $userable)
  {
    $count = $room->Messages()
      ->where('is_seen', 0)
      ->where('userable_id', $userable->id)
      ->where('userable_type', get_class($userable))
      ->count();

    return $count;
  }

  public function seeRoomMessages($room, $userable)
  {
    $room->Messages()
      ->where('is_seen', 0)
      ->where('userable_id', $userable->id)
      ->where('userable_type', get_class($userable))
      ->update(['is_seen' => true]);

    return true;
  }

  public function storeMessage($room, $sender, $message)
  {

    if (getType($message) == 'string') {
      $type = 'text';
      $body = $message;
    } else {
      $type = 'file';
      $body = $this->uploadAllTypes($message, 'rooms/' . $room->id);
    }

    //TODO: make shorter
    // create original message
    $newMessage = new Message;
    $newMessage->body = $body;
    $newMessage->room_id = $room->id;
    $newMessage->senderable_id = $sender->id;
    $newMessage->senderable_type = get_class($sender);
    $newMessage->type = $type;
    $newMessage->save();

    // update for sort by last message
    $room->last_message_id = $newMessage->id;
    $room->save();

    // create message relation for every room member
    foreach ($room->members->pluck('memberable') as $member) {

      $newMessageNoti = new MessageNotification;
      $newMessageNoti->message_id = $newMessage->id;
      $newMessageNoti->room_id = $room->id;
      $newMessageNoti->userable_id = $member->id;
      $newMessageNoti->userable_type = get_class($member);

      $newMessageNoti->is_flagged = 0;

      $is_sender = $sender->id == $member->id && get_class($sender) == get_class($member);

      //todo: $newMessageNoti->is_seen   = $is_sender ? 1 : (1 == $user->online ? 1 : 0);

      $newMessageNoti->is_seen = $is_sender ? 1 : 0;
      $newMessageNoti->is_sender = $is_sender ? 1 : 0;

      $newMessageNoti->save();
      if ($is_sender) {
        $senderLastMessage = $newMessageNoti;
      }

    }

    $senderLastMessage->load('originalMessage');
    $otherRoomUsers = $room->members
      ->where('memberable_type', '!=', get_class($sender))
      ->where('memberable_id', '!=', $sender->id);

    $senderLastMessage['other_users'] = $otherRoomUsers;
    return $senderLastMessage;
  }

  public function uploadRoomFile($room, $sender, $file)
  {

    $file_name = $this->uploadAllTypes($file, 'rooms/' . $room->id);
    $file_url = $this->getImage($file_name, 'rooms/' . $room->id);
    return ['file_name' => $file_name, 'file_url' => $file_url];
  }

  public function deleteMessageCopy($message, $userable)
  {
    //TODO: check if msg userable is the $userable
    $message->delete();
  }

  public function deleteConversation()
  {
    //? delete room messages for one user or leave room
  }

}
