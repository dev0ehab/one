<?php

namespace App\Services\Entity;

use App\Models\WalletChargeRequest;
use App\Models\WalletTransaction;
use App\Services\User\WalletService;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class WalletChargeRequestService extends BaseService
{
    use ResponseTrait;

    public function __construct(WalletChargeRequest $walletChargeRequest)
    {
        $this->model = $walletChargeRequest;
    }

    /**
     * Show the index page of the model.
     *
     */
    public function index()
    {
        if (request()->ajax()) {
            // get the rows from the database
            ${$this->model->getFolderName()} = $this->model::with(['wallet.walletable'])->latest()->search(request()->searchArray)->paginate(30);

            // determine the table view path
            $tableViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.table')
                ? 'admin.' . $this->model->getFolderName() . '.table'
                : 'admin.' . $this->model->camelModelName() . '.table';

            // render the table view
            $html = view($tableViewPath, get_defined_vars())->render();

            // return the rendered view as a json response
            return response()->json(['html' => $html]);
        }

        // determine the index view path
        $indexViewPath = view()->exists('admin.' . $this->model->getFolderName() . '.index')
            ? 'admin.' . $this->model->getFolderName() . '.index'
            : 'admin.' . $this->model->camelModelName() . '.index';

        // return the index view
        return view($indexViewPath);
    }

    public function show($id)
    {
        $row = WalletChargeRequest::with(['wallet.walletable', 'walletTransaction'])->findOrFail($id);

        if (request()->ajax()) {
            if (request()->type == 'main_data') {
                $html = view('admin.walletchargerequests.parts.main_data', compact('row'))->render();
            }

            return response()->json(['html' => $html]);
        }

        return view('admin.walletchargerequests.show', ['row' => $row]);
    }

    public function approve($id, $request)
    {
        $chargeRequest = WalletChargeRequest::with('wallet')->findOrFail($id);
        
        if ($chargeRequest->status !== 'pending') {
            return response()->json(['message' => __('admin.request_already_processed')], 400);
        }

        // Get the amount from request, fallback to original amount if not provided
        $amount = $request->input('amount', $chargeRequest->amount);
        
        // Validate amount
        if (!$amount || $amount <= 0) {
            return response()->json(['message' => __('admin.invalid_amount')], 400);
        }

        try {
            DB::beginTransaction();
            
            // Update the charge request status and amount
            $chargeRequest->update([
                'status' => 'approved',
                'amount' => $amount
            ]);
            
            // Create wallet transaction with the specified amount
            $walletService = new WalletService();
            $walletService->deposit($chargeRequest->wallet->walletable, $amount);
            
            // Get the latest transaction
            $latestTransaction = $chargeRequest->wallet->transactions()->latest()->first();
            
            // Link the transaction to the charge request
            $chargeRequest->update(['wallet_transaction_id' => $latestTransaction->id]);
            
            DB::commit();
            
            return response()->json(['message' => __('admin.request_approved_successfully')]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => __('admin.error_occurred')], 500);
        }
    }

    public function reject($id, $request)
    {
        $chargeRequest = WalletChargeRequest::findOrFail($id);
        
        if ($chargeRequest->status !== 'pending') {
            return response()->json(['message' => __('admin.request_already_processed')], 400);
        }

        $chargeRequest->update([
            'status' => 'rejected',
            'reason' => $request->reason ?? __('admin.request_rejected_by_admin')
        ]);

        return response()->json(['message' => __('admin.request_rejected_successfully')]);
    }
}
