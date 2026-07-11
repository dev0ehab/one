<?php

namespace App\Services\Entity;

use App\Mail\SendMail;
use App\Models\Client;
use App\Models\WalletTransaction;
use App\Notifications\BlockUser;
use App\Notifications\NotifyUser;
use App\Services\Entity\WalletService;
use App\Traits\ReportTrait;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class ClientService extends BaseService
{
    use ResponseTrait;

    public function __construct(Client $client)
    {
        $this->model = $client;
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
            $locationData = [
                'lat'              => $data['lat'],
                'lng'              => $data['lng'],
                'map_desc'         => $data['map_desc'],
                'country_id'       => $data['country_id'],
                'city_id'          => $data['city_id'],
                'is_main_location' => true,
                'name'             => 'Main Location',
            ];

            unset($data['lat'], $data['lng'], $data['map_desc'], $data['country_id'], $data['city_id']);

            $model = $this->model::create($data);
            $model->locations()->create($locationData);

            DB::commit();
            ReportTrait::addToLog('add_' . $this->model->lowerModelName());
            return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($request, $id)
    {
        try {
            DB::beginTransaction();
            $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
            $model = $this->model::findOrFail($id);
            $model->update($data);
            DB::commit();
            ReportTrait::addToLog('update_' . $this->model->lowerModelName());
            return ['status' => 'success', 'url' => route('admin.' . $this->model->getFolderName() . '.index')];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        $row = Client::with(['mainLocation.country', 'mainLocation.city', 'wallet'])->findOrFail($id);

        if (request()->ajax()) {
            if (request()->type == 'main_data') {
                $html = view('admin.clients.parts.main_data', compact('row'))->render();
            }


            if (request()->type == 'wallet') {
                $transactions = WalletTransaction::where('wallet_id', $row->wallet->id)->latest()->paginate(10);
                $html = view('admin.clients.parts.transactions', compact('transactions'))->render();
            }


            return response()->json(['html' => $html]);
        }

        return view('admin.clients.show', ['row' => $row]);
    }

    public function block($request)
    {
        $user = Client::findOrFail($request->id);
        $user->update(['is_blocked' => !$user->is_blocked]);
        if ($user->refresh()->is_blocked) {
            Notification::send($user, new BlockUser);
        }
        return response()->json(['message' => $user->refresh()->is_blocked ? __('admin.user_blocked') :  __('admin.user_unblocked')]);
    }

    public function notify($request)
    {
        if ($request->type == 'notifications' || $request->type == 'notify') {
            $client = ('all' == $request->id) ? Client::latest()->get() : Client::findOrFail($request->id);
            Notification::send($client, new NotifyUser($request->all()));
        } else {
            try {
                $mail = ('all' == $request->id) ? Client::where('email', '!=', null)->get()->pluck('email')->toArray() : Client::findOrFail($request->id)->email;
                Mail::to($mail)->send(new SendMail(['title' => 'اشعار اداري', 'message' => $request->message]));
            } catch (\Exception $e) {
                return response()->json(['msg' => $e->getMessage()])->setStatusCode(500);
            }
        }
        return true;
    }

    public function updateBalance($request, $id)
    {
        $user = Client::findOrFail($id);

        try {
            if ($request->type == 0) {
                (new WalletService())->deposit($user, $request->balance);
            } else {
                if (!$user->wallet || $user->wallet->balance < $request->balance) {
                    return response()->json(['msg' => __('admin.balance_not_enough')], 400);
                }
                (new WalletService())->deduct($user, $request->balance);
            }

            return response()->json(['msg' => __('admin.balance_updated'), 'balance' => $user->refresh()->wallet->balance . ' ' . __('site.currency')]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage()])->setStatusCode(400);
        }
    }

    public function toggleLanding($request)
    {
        $user = Client::findOrFail($request->id);
        $user->update(['is_shown_in_landing' => !$user->is_shown_in_landing]);
        return response()->json(['message' => $user->refresh()->is_shown_in_landing ? __('admin.client_shown_in_landing') : __('admin.client_hidden_from_landing')]);
    }

}
