<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Provider\BankAccount\UpdateBankAccountRequest;
use App\Services\Entity\WalletService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class BankAccountController extends Controller
{
    use ResponseTrait;

    public function __construct(protected WalletService $walletService)
    {
    }

    public function show(): JsonResponse
    {
        return $this->successData($this->walletService->getBankAccountForApi(auth()->user()));
    }

    public function update(UpdateBankAccountRequest $request): JsonResponse
    {
        $this->walletService->updateBankAccount(auth()->user(), $request->validated());

        return $this->successMsg(__('apis.success'));
    }
}
