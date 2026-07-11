<?php

namespace App\Services\Entity;

use App\Http\Filters\InvoiceFilter;
use App\Models\Invoice;
use App\Models\Client;
use App\Services\User\WalletService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class InvoiceService extends BaseService
{
    use ResponseTrait;

    private InvoiceFilter $filter;
    private WalletService $walletService;

    public function __construct(Invoice $invoice, InvoiceFilter $invoiceFilter, WalletService $walletService)
    {
        parent::__construct($invoice);
        $this->model = $invoice;
        $this->filter = $invoiceFilter;
        $this->walletService = $walletService;
    }

    public function indexApi()
    {
        $query = $this->model::filter($this->filter);

        if (request()->has('is_paginated') && !request()->is_paginated) {
            $data = $query->when(request()->limit, fn ($query) => $query->limit(request()->limit))->get();
        } else {
            $data = $query->paginate(request('perPage', 15));
        }

        return $this->apiCollectionResponse($data);
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
            $clientable = Client::where('phone', $data['phone'])->first();

            $model = $clientable->invoices()->create($data);

            if (isset($data['cashback']) && $data['cashback'] > 0) {
                $this->walletService->cacheBack($clientable, $data['cashback']);
            }

            DB::commit();

            return $this->apiResourceResponse($model->fresh());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
