<?php

namespace App\Http\Requests\Api\Client\Booking;

use App\Enums\BookingStatusEnum;
use App\Http\Requests\BaseRequest;
use App\Models\Booking;

class CancelBookingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'required|string|min:3|max:1000',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $booking = Booking::where('id', $this->route('id'))
                ->where('client_id', auth()->id())
                ->first();

            if (!$booking) {
                $validator->errors()->add('id', __('apis.booking_not_found'));
                return;
            }

            if (!in_array($booking->status, [
                BookingStatusEnum::NEW,
                BookingStatusEnum::PENDING_PAYMENT,
            ], true)) {
                $validator->errors()->add('status', __('apis.booking_cannot_cancel'));
            }
        });
    }
}
