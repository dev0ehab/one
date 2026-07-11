<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\providers\Store;
use App\Http\Requests\Admin\providers\StoreWorkDayRequest;
use App\Http\Requests\Admin\providers\Update;
use App\Models\City;
use App\Models\Provider;
use App\Services\Chat\ChatService;
use App\Services\Entity\ProviderService;

class ProviderController extends BaseController
{
    public function __construct(ProviderService $service)
    {
        parent::__construct($service, [
            'store'        => Store::class,
            'update'       => Update::class,
            'storeWorkDay' => StoreWorkDayRequest::class,
        ]);
    }

    public function store()
    {
        return $this->service->store($this->request);
    }

    public function update($id)
    {
        return $this->service->update($id, $this->request);
    }

    public function show($id)
    {
        return $this->service->show($id);
    }

    public function block()
    {
        return $this->service->block($this->request);
    }

    public function notify()
    {
        return $this->service->notify($this->request);
    }

    public function getCitiesByCountry()
    {
        $countryId = request('country_id');
        $cities = City::where('country_id', $countryId)
            ->get()
            ->map(function($city) {
                return [
                    'id' => $city->id,
                    'name' => $city->getTranslations('name')[app()->getLocale()] ?? $city->name
                ];
            });

        return response()->json($cities);
    }

    public function storeWorkDay(StoreWorkDayRequest $request, $providerId)
    {
        return $this->service->storeWorkDay($request, $providerId);
    }


    public function accept()
    {
        return $this->service->accept($this->request);
    }

    public function reject()
    {
        return $this->service->reject($this->request);
    }

    public function showRoom(Provider $provider)
    {
        $order = $provider;

        $chat = new ChatService();

        $user = auth('admin')->user();

        $room = $provider->ensureAdminChatRoom();
        $room->load('members.memberable');

        $chat->seeRoomMessages($room, $user);

        $messages = $chat->getRoomMessagesResource($room, $user);

        $members = $chat->getOtherRoomMembers($room, $user);

        return view('admin.providers.room', compact('user', 'room', 'messages', 'members', 'order'));
    }

}
