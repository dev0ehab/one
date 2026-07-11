<?php

namespace App\Services\Entity;

use App\Models\SMS;
use App\Traits\ReportTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SmsService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param SMS $SMS
     */
    public function __construct(SMS $sms)
    {
        $this->model = $sms;
    }

    public function index(): View
    {
        $smss = SMS::latest()->get();
        return view('admin.sms.index', compact('smss'));
    }

    public function change($request): JsonResponse
    {
        $sms = SMS::findOrFail($request->id);
        $disableAll = SMS::get()->each->update(['active' => 0]);

        if ($disableAll) {
            $sms->update(['active' => 1]);
        }

        return response()->json();
    }

    public function update($request, $id): JsonResponse|array
    {
        SMS::findOrFail($id)->update($request->validated());
        ReportTrait::addToLog('تعديل باقة رسائل');
        
        return response()->json();
    }
}
