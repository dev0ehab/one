<?php

namespace App\Traits;

use App\Enums\NotificationTypeEnum;

trait NotificationMessageTrait
{

    /**
     * Get the title of the notification
     *
     * @param string $type
     * @param string $local
     * @param array|null $notification_data
     * @return string
     */
    public function getTitle(string $type, string $local = 'ar', array $notification_data = null): string
    {
        if ($type === 'user_notify' && isset($notification_data['title'])) {
            return $notification_data['title'];
        }

        if (in_array($type, NotificationTypeEnum::toArray())) {
            return trans($this->data["title_" . $local], [], $local);
        }


        return trans('notification.title_' . $type, [], $local);
    }


    /**
     * Get the body of the notification
     *
     * @param array $notification_data
     * @param string $local
     * @return string
     */
    public function getBody(array $notification_data, string $local = 'ar'): string
    {
        if (in_array($notification_data['type'], NotificationTypeEnum::toArray())) {
            return trans($this->data["body_" . $local], [], $local);
        }

        switch ($notification_data['type']) {
            case 'admin_notify':
                return $notification_data['body_' . $local] ?? '';
            case 'user_notify':
                return $notification_data['body'] ?? '';
            default:
                return $this->transTypeToBody($notification_data, $local);
        }
    }


    /**
     * Translate the notification type to the body
     *
     * @param array $notification_data
     * @param string $local
     * @return string
     */
    private function transTypeToBody(array $notification_data, string $local): string
    {
        $transData = array_filter([
            'order_num'          => $notification_data['order_num'] ?? null,
            'order_number'       => $notification_data['order_number'] ?? $notification_data['order_num'] ?? null,
            'reservation_number' => $notification_data['reservation_number'] ?? null,
            'user_name'          => $notification_data['user_name'] ?? null,
            'id'                 => $notification_data['id'] ?? null,
            'rate'               => $notification_data['rate'] ?? null,
            'type'               => $notification_data['type'] ?? null,
            'amount'             => $notification_data['amount'] ?? null,
            'reason'             => $notification_data['reason'] ?? null,
            'rejection_reason'   => $notification_data['rejection_reason'] ?? $notification_data['reason'] ?? null,
            'settlement_id'      => $notification_data['settlement_id'] ?? null,
            'settlement_number'  => $notification_data['settlement_number'] ?? $notification_data['settlement_id'] ?? null,
            'name'               => $notification_data['name'] ?? null,
            'reply'              => $notification_data['reply'] ?? null,
            'title'              => $notification_data['title'] ?? null,
            'balance'            => $notification_data['balance'] ?? null,
            'threshold'          => $notification_data['threshold'] ?? null,
            'plate'              => $notification_data['plate'] ?? null,
            'order_id'           => $notification_data['order_id'] ?? null,
        ], fn ($v) => $v !== null);

        return trans('notification.body_' . $notification_data['type'], $transData, $local);
    }
}
