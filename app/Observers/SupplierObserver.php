<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Provider;
use App\Notifications\GlobalNotification;
use App\Enums\NotificationTypeEnum;

class SupplierObserver implements \Illuminate\Contracts\Queue\ShouldQueue
{
    public bool $afterCommit = true;

    protected array $entityKeys = [
        Client::class   => 'client',
        Provider::class => 'provider',
    ];

    public function created($model): void
    {
        $this->notifyAdminsOnCreate($model);
    }

    public function updated($model): void
    {
        if ($model->wasChanged('is_blocked') && $model->is_blocked) {
            $this->notifySelfBlocked($model);
        }

        if ($model->wasChanged('is_approved') && $model->is_approved && !($model instanceof Client)) {
            $this->notifySelfApproved($model);
        }

        if (
            $model->is_approved === false
            && $model->wasChanged('reject_reason')
            && !empty($model->reject_reason)
            && !($model instanceof Client)
        ) {
            $this->notifySelfRejected($model);
        }

        if (
            $model->wasChanged('reject_reason')
            && is_null($model->reject_reason)
            && !($model instanceof Client)
        ) {
            $this->notifyAdminsNeedsApproval($model);
        }
    }

    protected function notifySelfBlocked($model): void
    {
        if (!method_exists($model, 'notify')) {
            return;
        }

        $model->notify(
            new GlobalNotification(
                get_class($model),
                $model->id,
                __('notification.title_block'),
                __('notification.title_block'),
                __('notification.body_block'),
                __('notification.body_block'),
                'blocked'
            )
        );
    }

    protected function notifySelfApproved($model): void
    {
        if (!method_exists($model, 'notify')) {
            return;
        }

        $entityKey = $this->entityKeys[get_class($model)] ?? null;
        if (!$entityKey) {
            return;
        }

        $titleAr = __('notification.' . $entityKey . '_approved_title_ar', ['id' => $model->id]);
        $titleEn = __('notification.' . $entityKey . '_approved_title_en', ['id' => $model->id]);
        $bodyAr = __('notification.' . $entityKey . '_approved_body_ar', ['id' => $model->id]);
        $bodyEn = __('notification.' . $entityKey . '_approved_body_en', ['id' => $model->id]);

        $model->notify(
            new GlobalNotification(
                get_class($model),
                $model->id,
                $titleAr,
                $titleEn,
                $bodyAr,
                $bodyEn,
                NotificationTypeEnum::EntityApproved
            )
        );
    }

    protected function notifySelfRejected($model): void
    {
        if (!method_exists($model, 'notify')) {
            return;
        }

        $entityKey = $this->entityKeys[get_class($model)] ?? null;
        if (!$entityKey) {
            return;
        }

        $titleAr = __('notification.' . $entityKey . '_rejected_title_ar', ['id' => $model->id]);
        $titleEn = __('notification.' . $entityKey . '_rejected_title_en', ['id' => $model->id]);
        $bodyAr = __('notification.' . $entityKey . '_rejected_body_ar', ['id' => $model->id, 'reason' => $model->reject_reason]);
        $bodyEn = __('notification.' . $entityKey . '_rejected_body_en', ['id' => $model->id, 'reason' => $model->reject_reason]);

        $model->notify(
            new GlobalNotification(
                get_class($model),
                $model->id,
                $titleAr,
                $titleEn,
                $bodyAr,
                $bodyEn,
                NotificationTypeEnum::EntityRejected
            )
        );
    }

    protected function notifyAdminsOnCreate($model): void
    {
        $entityKey = $this->entityKeys[get_class($model)] ?? null;
        if (!$entityKey) {
            return;
        }

        $admins = Admin::where('is_notify', true)->get();
        if ($admins->isEmpty()) {
            return;
        }

        $titleAr = __('notification.' . $entityKey . '_created_title_ar', ['id' => $model->id]);
        $titleEn = __('notification.' . $entityKey . '_created_title_en', ['id' => $model->id]);
        $bodyAr = __('notification.' . $entityKey . '_created_body_ar', ['name' => $model->name]);
        $bodyEn = __('notification.' . $entityKey . '_created_body_en', ['name' => $model->name]);

        foreach ($admins as $admin) {
            $admin->notify(
                new GlobalNotification(
                    get_class($model),
                    $model->id,
                    $titleAr,
                    $titleEn,
                    $bodyAr,
                    $bodyEn,
                    NotificationTypeEnum::EntityCreated
                )
            );
        }
    }

    protected function notifyAdminsNeedsApproval($model): void
    {
        $entityKey = $this->entityKeys[get_class($model)] ?? null;
        if (!$entityKey) {
            return;
        }

        $admins = Admin::where('is_notify', true)->get();
        if ($admins->isEmpty()) {
            return;
        }

        $titleAr = __('notification.' . $entityKey . '_needs_approval_title_ar', ['id' => $model->id]);
        $titleEn = __('notification.' . $entityKey . '_needs_approval_title_en', ['id' => $model->id]);
        $bodyAr = __('notification.' . $entityKey . '_needs_approval_body_ar', ['id' => $model->id]);
        $bodyEn = __('notification.' . $entityKey . '_needs_approval_body_en', ['id' => $model->id]);

        foreach ($admins as $admin) {
            $admin->notify(
                new GlobalNotification(
                    get_class($model),
                    $model->id,
                    $titleAr,
                    $titleEn,
                    $bodyAr,
                    $bodyEn,
                    NotificationTypeEnum::EntityNeedsApproval
                )
            );
        }
    }
}
