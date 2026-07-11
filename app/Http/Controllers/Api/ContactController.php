<?php

namespace App\Http\Controllers\Api;

use App\Enums\ContactType;
use App\Enums\NotificationTypeEnum;
use App\Models\Contact;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Http\Requests\Api\Common\Contact\SendContactRequest;
use App\Notifications\GlobalNotification;

class ContactController extends Controller
{
    use ResponseTrait;

    public function contact(SendContactRequest $request)
    {
        $model = Contact::create($request->validated());

        $message = match ($request->type) {
            ContactType::CONTACT => __('apis.contact_send'),
            ContactType::COMPLAINT => __('apis.complaint_send'),
            ContactType::SUGGESTION => __('apis.suggestion_send'),
        };

        if (auth()->check()) {

            $titleAr = __('notification.new_contact');
            $titleEn = __('notification.new_contact');
            $bodyAr  = __('notification.new_contact_us_Message_body' , ['name' => $model->name]);
            $bodyEn  = __('notification.new_contact_us_Message_body' , ['name' => $model->name]);

            $admin = Admin::where('is_notify', true)->firstOrfail();
            if ($admin) {
                $admin->notify(
                    new GlobalNotification(
                        get_class($model),
                        $model->id,
                        $titleAr,
                        $titleEn,
                        $bodyAr,
                        $bodyEn,
                        NotificationTypeEnum::ContactNotify
                    )
                );
            }
        }



        return $this->successMsg($message);
    }
}
