<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AdStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\ProviderAd;
use App\Services\Entity\AdService;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProviderAdController extends Controller
{
    use ResponseTrait;

    public function __construct(protected AdService $adService)
    {
    }

    public function index(): mixed
    {
        $status = request('status');

        $query = ProviderAd::with(['provider:id,trade_name,image', 'images'])
            ->latest();

        if ($status === 'pending') {
            $query->where('status', AdStatusEnum::PENDING);
        } elseif ($status === 'approved') {
            $query->where('status', AdStatusEnum::APPROVED);
        } elseif ($status === 'rejected') {
            $query->where('status', AdStatusEnum::REJECTED);
        }

        if (request()->ajax()) {
            $ads  = $query->paginate(20);
            $html = view('admin.provider_ads.table', compact('ads'))->render();
            return response()->json(['html' => $html]);
        }

        return view('admin.provider_ads.index');
    }

    public function show(int $id): mixed
    {
        $ad = ProviderAd::with(['provider', 'images', 'subscription.package'])->findOrFail($id);
        return view('admin.provider_ads.show', compact('ad'));
    }

    public function approve(int $id): JsonResponse
    {
        $result = $this->adService->approveAd($id);

        if ($result['status'] === 'fail') {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json(['message' => __('admin.ad_approved')]);
    }

    public function reject(Request $request, int $id): JsonResponse
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $result = $this->adService->rejectAd($id, $request->reason);

        if ($result['status'] === 'fail') {
            return response()->json(['message' => $result['message']], 422);
        }

        return response()->json(['message' => __('admin.ad_rejected')]);
    }
}
