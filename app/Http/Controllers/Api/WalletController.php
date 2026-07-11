<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Wallet\ChargeWalletRequest;
use App\Http\Requests\Wallet\WithdrawWalletRequest;
use App\Models\Provider;
use App\Http\Resources\Api\Client\Wallets\TransactionsCollection;
use App\Services\Entity\WalletService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    use ResponseTrait;

    public function __construct(protected WalletService $service)
    {
    }

    public function show(): JsonResponse
    {
        return $this->successData($this->service->getWalletForApi(auth()->user()));
    }

    public function transactions(): JsonResponse
    {
        return $this->successData(
            TransactionsCollection::make($this->service->getTransactions(auth()->user()))
        );
    }

    public function charge(ChargeWalletRequest $request): JsonResponse
    {
        $result = $this->service->createChargeRequest(auth()->user(), $request->validated());

        return $this->response('success', $result['message'], $result['data']);
    }

    public function withdraw(WithdrawWalletRequest $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user instanceof Provider) {
            abort(403);
        }

        $result = $this->service->createWithdrawalRequest($user, (float) $request->validated('amount'));

        return $this->response('success', $result['message']);
    }

}
