<?php

namespace App\Http\Resources\Api\Client\Booking;

use App\Enums\BookingStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                        => $this->id,
            'booking_number'            => $this->booking_number,
            'status'                    => $this->status,
            'scheduled_at'              => $this->scheduled_at?->format('Y-m-d H:i'),
            'date'                      => $this->scheduled_at?->format('Y-m-d'),
            'time'                      => $this->scheduled_at?->format('H:i'),
            'ad_id'                     => $this->provider_ad_id,
            'service_title'             => $this->service_title,
            'service_description'       => $this->service_description,
            'notes'                     => $this->service_description,
            'price'                     => (float) $this->price,
            'payment_method'            => $this->payment_method,
            'paid_at'                   => $this->paid_at?->format('Y-m-d H:i'),
            'is_paid'                   => $this->paid_at !== null,
            'address'                   => $this->address,
            'cancel_reason'             => $this->cancel_reason,
            'additional_invoice_amount'   => $this->additional_invoice_amount
                ? (float) $this->additional_invoice_amount
                : null,
            'additional_invoice_reason'   => $this->additional_invoice_reason,
            'additional_invoice_status'   => $this->additional_invoice_status,
            'additional_invoice_status_label' => $this->additional_invoice_status
                ? __('apis.additional_invoice_status_' . $this->additional_invoice_status)
                : null,
            'additional_invoice_payment_deadline' => $this->additional_invoice_payment_deadline?->format('Y-m-d H:i'),
            'additional_invoice_paid_at'  => $this->additional_invoice_paid_at?->format('Y-m-d H:i'),
            'additional_invoice_payment_method' => $this->additional_invoice_payment_method,
            'additional_invoice_client_reject_reason' => $this->additional_invoice_client_reject_reason,
            'can_pay_additional_invoice'  => in_array($this->additional_invoice_status, \App\Enums\AdditionalInvoiceStatusEnum::awaitingPayment(), true),
            'provider_name'             => $this->whenLoaded('provider', fn () => $this->provider->trade_name),
            'provider_phone'            => $this->whenLoaded('provider', fn () => $this->provider->phone),
            'provider_image'            => $this->whenLoaded('provider', fn () => $this->provider->image),
            'client_name'               => $this->whenLoaded('client', fn () => $this->client->name),
            'client_phone'              => $this->whenLoaded('client', fn () => $this->client->phone),
            'client_image'              => $this->whenLoaded('client', fn () => $this->client->image),
            'images'                    => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($img) => $img->image)->values();
            }, []),
            'created_at'                => $this->created_at?->format('Y-m-d H:i'),
            'can_rate'                  => $this->status === BookingStatusEnum::COMPLETED && $this->rate === null,
            'client_rating'             => BookingRateResource::make($this->whenLoaded('rate')),
        ];
    }
}
