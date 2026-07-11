<?php

namespace App\Services\Entity;

use App\Enums\AdditionalInvoiceStatusEnum;
use App\Enums\BookingStatusEnum;
use App\Http\Filters\BookingFilter;
use App\Http\Requests\Api\Client\Booking\StoreBookingRequest;
use App\Http\Resources\Api\Client\Booking\BookingsCollection;
use App\Http\Resources\Api\Client\Booking\BookingResource;
use App\Services\Booking\BookingAvailabilityService;
use App\Enums\NotificationTypeEnum;
use App\Enums\PaymentTransactions;
use App\Enums\WalletTransactionType;
use App\Models\Booking;
use App\Models\BookingImage;
use App\Services\Notification\BookingNotificationService;
use App\Models\Client;
use App\Services\PaymentGateway\PaymentService;
use App\Services\Entity\WalletService;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookingService extends BaseService
{
    public function __construct(Booking $booking, BookingFilter $filter)
    {
        parent::__construct($booking);
        $this->filter = $filter;
    }

    protected function baseQuery()
    {
        return $this->model::where('provider_id', auth()->id())->filter($this->filter)
            ->with(['provider', 'images', 'rate']);
    }

    public function indexApiForClient()
    {
        $tab = request('tab', 'current');

        request()->merge([
            'mine' => true,
            'tab'  => $tab,
        ]);

        $query = $this->model::where('client_id', auth()->id())->filter($this->filter)
        ->with(['provider', 'images', 'rate']);

        $query = match ($tab) {
            'past'      => $query->orderByDesc('scheduled_at'),
            'cancelled' => $query->orderByDesc('scheduled_at'),
            default     => $query->orderBy('scheduled_at'),
        };

        $paginated = $query->paginate(request()->paginate ?? 10);

        $client = auth()->user();
        $showEmpty = $paginated->total() === 0
            && !$client->bookings_empty_banner_dismissed
            && !request()->filled('tab');

        return new BookingsCollection($paginated, [
            'show_empty_message' => $showEmpty,
            'empty_message'      => __('apis.bookings_empty_message'),
        ]);
    }

    public function indexApiForProvider()
    {
        request()->merge(['provider' => true]);

        $query = $this->baseQuery()->with('client')->orderByDesc('created_at');

        return new BookingsCollection($query->paginate(request()->paginate ?? 10));
    }

    public function showApi($id)
    {
        $booking = $this->findClientBooking($id);
        return BookingResource::make($booking);
    }

    public function availabilityForProvider(int $providerId, ?int $daysAhead = null): array
    {
        $provider = \App\Models\Provider::query()
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->where('is_approved', 1)
            ->findOrFail($providerId);

        return app(BookingAvailabilityService::class)->getAvailability($provider, $daysAhead);
    }

    public function store($request): array
    {
        DB::beginTransaction();

        try {
            if ($request instanceof StoreBookingRequest) {
                $ad = $request->providerAd();
                $provider = $request->provider();

                $booking = $this->model::create([
                    'client_id'           => auth()->id(),
                    'provider_id'         => $provider->id,
                    'provider_ad_id'      => $ad->id,
                    'status'              => BookingStatusEnum::NEW,
                    'scheduled_at'        => $request->scheduledAt(),
                    'service_title'       => $ad->title,
                    'service_description' => $request->notes,
                    'price'               => (float) $ad->price,
                    'address'             => $provider->map_desc,
                ]);
            } else {
                $data = method_exists($request, 'validated') ? $request->validated() : $request->all();

                $booking = $this->model::create([
                    'client_id'           => auth()->id(),
                    'provider_id'         => $data['provider_id'],
                    'status'              => BookingStatusEnum::NEW,
                    'scheduled_at'        => $data['scheduled_at'],
                    'service_title'       => $data['service_title'],
                    'service_description' => $data['service_description'] ?? null,
                    'price'               => $data['price'],
                    'address'             => $data['address'] ?? null,
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        BookingImage::create([
                            'booking_id' => $booking->id,
                            'image'      => $image,
                        ]);
                    }
                }
            }

            DB::commit();

            $booking->load(['provider', 'providerAd']);
            BookingNotificationService::notifyProvider(
                $booking,
                NotificationTypeEnum::BookingNewReceived
            );

            return [
                'status'  => 'success',
                'message' => __('apis.booking_created'),
                'data'    => BookingResource::make($booking->load(['provider', 'providerAd', 'images'])),
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function pay($request, int $id): array
    {
        $booking = $this->findClientBooking($id);

        if ($booking->status !== BookingStatusEnum::PENDING_PAYMENT) {
            return ['status' => 'fail', 'message' => __('apis.booking_payment_not_required')];
        }

        $client = auth()->user();
        $amount = (float) $booking->price;

        if ($amount <= 0) {
            return $this->markBookingPaid($booking, 'wallet');
        }

        if ($request->payment_method === 'wallet') {
            return $this->payBookingWithWallet($booking, $client, $amount);
        }

        return $this->payBookingWithGateway($booking, $client, $amount);
    }

    protected function payBookingWithWallet(Booking $booking, Client $client, float $amount): array
    {
        try {
            DB::beginTransaction();

            (new WalletService())->deduct($client, $amount, WalletTransactionType::BOOKING);

            $result = $this->markBookingPaid($booking, 'wallet');

            DB::commit();

            return $result;
        } catch (HttpException $e) {
            DB::rollBack();

            return ['status' => 'fail', 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function payBookingWithGateway(Booking $booking, Client $client, float $amount): array
    {
        $paymentService = new PaymentService();
        $gateway = $paymentService->create();

        $payment = $gateway->createPayment(['amount' => $amount], $client);

        $paymentService->createPaymentTransaction(
            $amount,
            PaymentTransactions::PAYBOOKING,
            Booking::class,
            $booking->id,
            $payment['transaction_id'],
            $payment['paymentResponse'],
            $payment['payment_name']
        );

        return [
            'status'  => 'success',
            'message' => __('apis.booking_payment_redirect'),
            'data'    => [
                'redirect_url'   => $payment['redirect_url'],
                'transaction_id' => $payment['transaction_id'],
                'booking'        => BookingResource::make($booking),
            ],
        ];
    }

    protected function markBookingPaid(Booking $booking, string $paymentMethod): array
    {
        $booking->update([
            'status'         => BookingStatusEnum::IN_PROGRESS,
            'payment_method' => $paymentMethod,
            'paid_at'        => now(),
        ]);

        BookingNotificationService::notifyPaid($booking->fresh());

        return [
            'status'  => 'success',
            'message' => __('apis.booking_paid'),
            'data'    => BookingResource::make($booking->fresh(['provider', 'images'])),
        ];
    }

    public function cancel($request, $id): array
    {
        $booking = $this->findClientBooking($id);

        if (!in_array($booking->status, BookingStatusEnum::currentTabStatuses(), true)) {
            return ['status' => 'fail', 'message' => __('apis.booking_cannot_cancel')];
        }

        $booking->update([
            'status'        => BookingStatusEnum::CANCELLED,
            'cancel_reason' => $request->reason,
        ]);

        BookingNotificationService::notifyProvider(
            $booking->fresh(),
            NotificationTypeEnum::BookingCancelled
        );

        return [
            'status'  => 'success',
            'message' => __('apis.booking_cancelled'),
            'data'    => BookingResource::make($booking->fresh(['provider', 'images'])),
        ];
    }

    public function acceptAdditionalInvoice($id): array
    {
        $booking = $this->findClientBooking($id);

        if ($booking->status !== BookingStatusEnum::IN_PROGRESS
            || $booking->additional_invoice_status !== AdditionalInvoiceStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        $deadline = now()->addHours($this->additionalInvoicePaymentHours());

        $booking->update([
            'additional_invoice_status'          => AdditionalInvoiceStatusEnum::ACCEPTED,
            'additional_invoice_payment_deadline' => $deadline,
            'additional_invoice_client_reject_reason' => null,
        ]);

        return [
            'status'  => 'success',
            'message' => __('apis.additional_invoice_accepted'),
            'data'    => BookingResource::make($booking->fresh(['provider', 'images'])),
        ];
    }

    public function rejectAdditionalInvoice($request, $id): array
    {
        $booking = $this->findClientBooking($id);

        if ($booking->status !== BookingStatusEnum::IN_PROGRESS
            || $booking->additional_invoice_status !== AdditionalInvoiceStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        $booking->update([
            'additional_invoice_status'               => AdditionalInvoiceStatusEnum::REJECTED,
            'additional_invoice_client_reject_reason'   => $request->reason ?? null,
            'additional_invoice_payment_deadline'     => null,
        ]);

        return [
            'status'  => 'success',
            'message' => __('apis.additional_invoice_rejected'),
            'data'    => BookingResource::make($booking->fresh(['provider', 'images'])),
        ];
    }

    public function payAdditionalInvoice($request, int $id): array
    {
        $booking = $this->findClientBooking($id);
        $this->syncAdditionalInvoiceOverdue($booking);

        if ($booking->status !== BookingStatusEnum::IN_PROGRESS
            || !in_array($booking->additional_invoice_status, AdditionalInvoiceStatusEnum::awaitingPayment(), true)) {
            return ['status' => 'fail', 'message' => __('apis.additional_invoice_payment_not_required')];
        }

        $client = auth()->user();
        $amount = (float) $booking->additional_invoice_amount;

        if ($amount <= 0) {
            return $this->markAdditionalInvoicePaid($booking, 'wallet');
        }

        if ($request->payment_method === 'wallet') {
            return $this->payAdditionalInvoiceWithWallet($booking, $client, $amount);
        }

        return $this->payAdditionalInvoiceWithGateway($booking, $client, $amount);
    }

    protected function payAdditionalInvoiceWithWallet(Booking $booking, Client $client, float $amount): array
    {
        try {
            DB::beginTransaction();

            (new WalletService())->deduct($client, $amount, WalletTransactionType::BOOKING);

            $result = $this->markAdditionalInvoicePaid($booking, 'wallet');

            DB::commit();

            return $result;
        } catch (HttpException $e) {
            DB::rollBack();

            return ['status' => 'fail', 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function payAdditionalInvoiceWithGateway(Booking $booking, Client $client, float $amount): array
    {
        $paymentService = new PaymentService();
        $gateway = $paymentService->create();
        $payment = $gateway->createPayment(['amount' => $amount], $client);

        $paymentService->createPaymentTransaction(
            $amount,
            PaymentTransactions::PAYADDITIONALINVOICE,
            Booking::class,
            $booking->id,
            $payment['transaction_id'],
            $payment['paymentResponse'],
            $payment['payment_name']
        );

        return [
            'status'  => 'success',
            'message' => __('apis.additional_invoice_payment_redirect'),
            'data'    => [
                'redirect_url'   => $payment['redirect_url'],
                'transaction_id' => $payment['transaction_id'],
                'booking'        => BookingResource::make($booking),
            ],
        ];
    }

    protected function markAdditionalInvoicePaid(Booking $booking, string $paymentMethod): array
    {
        $amount = (float) $booking->additional_invoice_amount;
        $commissionPercent = (float) (settings()['additional_invoice_commission_percent'] ?? 0);
        $commission = round($amount * $commissionPercent / 100, 2);

        $booking->update([
            'additional_invoice_status'         => AdditionalInvoiceStatusEnum::PAID,
            'additional_invoice_paid_at'        => now(),
            'additional_invoice_payment_method' => $paymentMethod,
            'additional_invoice_commission'     => $commission,
        ]);

        return [
            'status'  => 'success',
            'message' => __('apis.additional_invoice_paid'),
            'data'    => BookingResource::make($booking->fresh(['provider', 'images'])),
        ];
    }

    public function rateProvider($request, int $id): array
    {
        $booking = $this->findClientBooking($id);
        $result = (new RateService())->rateBooking(
            $booking,
            auth()->id(),
            (int) $request->rate,
            $request->comment
        );

        if ($result['status'] === 'fail') {
            return $result;
        }

        return [
            'status'  => 'success',
            'message' => $result['message'],
            'data'    => BookingResource::make($booking->fresh(['provider', 'images', 'rate'])),
        ];
    }

    public function dismissEmptyBanner(): array
    {
        auth()->user()->update(['bookings_empty_banner_dismissed' => true]);

        return ['status' => 'success', 'message' => __('apis.success')];
    }

    public function providerAccept($id): array
    {
        $booking = $this->findProviderBooking($id);

        if ($booking->status !== BookingStatusEnum::NEW) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        $booking->update(['status' => BookingStatusEnum::PENDING_PAYMENT]);

        BookingNotificationService::notifyClient($booking->fresh(), NotificationTypeEnum::BookingAccepted);

        return [
            'status'  => 'success',
            'message' => __('apis.booking_accepted'),
            'data'    => BookingResource::make($booking->fresh(['client', 'images'])),
        ];
    }

    public function providerReject($id, ?string $reason = null): array
    {
        $booking = $this->findProviderBooking($id);

        if ($booking->status !== BookingStatusEnum::NEW) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        $booking->update([
            'status'        => BookingStatusEnum::CANCELLED,
            'cancel_reason' => $reason,
        ]);

        BookingNotificationService::notifyClient($booking->fresh(), NotificationTypeEnum::BookingRejected);

        return [
            'status'  => 'success',
            'message' => __('apis.booking_rejected'),
            'data'    => BookingResource::make($booking->fresh(['client', 'images'])),
        ];
    }

    public function providerIssueAdditionalInvoice($id, float $amount, string $reason): array
    {
        $booking = $this->findProviderBooking($id);

        if ($booking->status !== BookingStatusEnum::IN_PROGRESS) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        if (!in_array($booking->additional_invoice_status, [
            null,
            AdditionalInvoiceStatusEnum::REJECTED,
            AdditionalInvoiceStatusEnum::PAYMENT_OVERDUE,
            AdditionalInvoiceStatusEnum::PAID,
        ], true)) {
            return ['status' => 'fail', 'message' => __('apis.additional_invoice_pending_exists')];
        }

        $booking->update([
            'additional_invoice_amount'               => $amount,
            'additional_invoice_reason'               => $reason,
            'additional_invoice_status'               => AdditionalInvoiceStatusEnum::PENDING,
            'additional_invoice_client_reject_reason' => null,
            'additional_invoice_payment_deadline'     => null,
            'additional_invoice_paid_at'              => null,
            'additional_invoice_payment_method'       => null,
            'additional_invoice_commission'           => null,
        ]);

        BookingNotificationService::notifyClient(
            $booking->fresh(['client']),
            NotificationTypeEnum::BookingAdditionalInvoiceIssued,
            ['amount' => $amount]
        );

        return [
            'status'  => 'success',
            'message' => __('apis.additional_invoice_issued'),
            'data'    => BookingResource::make($booking->fresh(['client', 'images'])),
        ];
    }

    public function providerComplete($id): array
    {
        $booking = $this->findProviderBooking($id);
        $this->syncAdditionalInvoiceOverdue($booking);

        if ($booking->status !== BookingStatusEnum::IN_PROGRESS) {
            return ['status' => 'fail', 'message' => __('apis.booking_invalid_action')];
        }

        if (in_array($booking->additional_invoice_status, [
            AdditionalInvoiceStatusEnum::PENDING,
            AdditionalInvoiceStatusEnum::ACCEPTED,
            AdditionalInvoiceStatusEnum::PAYMENT_OVERDUE,
        ], true)) {
            return ['status' => 'fail', 'message' => __('apis.booking_pending_additional_invoice')];
        }

        $booking->update(['status' => BookingStatusEnum::COMPLETED]);

        BookingNotificationService::notifyClient($booking->fresh(), NotificationTypeEnum::BookingCompleted);

        return [
            'status'  => 'success',
            'message' => __('apis.booking_completed'),
            'data'    => BookingResource::make($booking->fresh(['client', 'images'])),
        ];
    }

    protected function findClientBooking($id): Booking
    {
        $booking = $this->model::with(['provider', 'images'])
            ->where('client_id', auth()->id())
            ->findOrFail($id);

        $this->syncAdditionalInvoiceOverdue($booking);

        return $booking->fresh(['provider', 'images']);
    }

    protected function findProviderBooking($id): Booking
    {
        $booking = $this->model::with(['client', 'images'])
            ->where('provider_id', auth()->id())
            ->findOrFail($id);

        $this->syncAdditionalInvoiceOverdue($booking);

        return $booking->fresh(['client', 'images']);
    }

    protected function syncAdditionalInvoiceOverdue(Booking $booking): void
    {
        if ($booking->additional_invoice_status !== AdditionalInvoiceStatusEnum::ACCEPTED
            || !$booking->additional_invoice_payment_deadline
            || $booking->additional_invoice_payment_deadline->isFuture()) {
            return;
        }

        $wasAccepted = $booking->additional_invoice_status === AdditionalInvoiceStatusEnum::ACCEPTED;

        $booking->update(['additional_invoice_status' => AdditionalInvoiceStatusEnum::PAYMENT_OVERDUE]);

        if ($wasAccepted) {
            BookingNotificationService::notifyClient(
                $booking->fresh(['client']),
                NotificationTypeEnum::BookingAdditionalInvoicePaymentDue
            );
        }
    }

    protected function additionalInvoicePaymentHours(): int
    {
        return max(1, (int) (settings()['additional_invoice_payment_hours'] ?? 72));
    }
}
