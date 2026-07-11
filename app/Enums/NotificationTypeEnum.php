<?php

namespace App\Enums;

/**
 * Class CouponTypeEnum
 *
 * @method static string all()
 * @method static string|null nameFor($value)
 * @method static array toArray()
 * @method static array forApi()
 * @method static string slug(int $value)
 */
class NotificationTypeEnum extends Base
{

    const OrderCreated = 'order_created';
    const OrderPendingApproval = 'order_pending_approval';
    const OrderAccepted = 'order_accepted';
    const OrderRejected = 'order_rejected';
    const OrderPendingInsuranceApproval = 'order_pending_insurance_approval';
    const OrderPendingOrganizationApproval = 'order_pending_organization_approval';
    const OrderPendingPayment = 'order_pending_payment';
    const OrderCompleted = 'order_completed';
    const OrderCancelled = 'order_cancelled';

    const OrderPreparing = 'order_preparing';
    const OrderDeliveredToDelivery = 'order_delivered_to_delivery';
    const OrderOnTheWay = 'order_on_the_way';

    const ReservationCreated = 'reservation_created';
    const ReservationPendingApproval = 'reservation_pending_approval';
    const ReservationAccepted = 'reservation_accepted';
    const ReservationRejected = 'reservation_rejected';
    const ReservationPendingInsuranceApproval = 'reservation_pending_insurance_approval';
    const ReservationPendingOrganizationApproval = 'reservation_pending_organization_approval';
    const ReservationPendingPayment = 'reservation_pending_payment';
    const ReservationCompleted = 'reservation_completed';
    const ReservationCancelled = 'reservation_cancelled';

    const WalletCharge          = 'wallet_charge';
    const WalletTransferDeposit = 'wallet_transfer_deposit';
    const WalletTransferWithdraw = 'wallet_transfer_withdraw';
    const WalletTransferWithdrawal = 'wallet_transfer_withdrawal';
    const WalletWithdraw = 'wallet_withdraw';
    const WalletCashback        = 'wallet_cashback';
    const WalletDebt            = 'wallet_debt';
    const WalletChargeRequestRejected = 'wallet_charge_request_rejected';
    const WalletLowBalance            = 'wallet_low_balance';
    const WalletBooking               = 'wallet_booking';

    const ContactReplied        = 'contact_replied';

    const BookingNewReceived                   = 'booking_new_received';
    const BookingAccepted                      = 'booking_accepted';
    const BookingRejected                      = 'booking_rejected';
    const BookingPaid                          = 'booking_paid';
    const BookingCompleted                     = 'booking_completed';
    const BookingCancelled                     = 'booking_cancelled';
    const BookingAdditionalInvoiceIssued       = 'booking_additional_invoice_issued';
    const BookingAdditionalInvoicePaymentDue   = 'booking_additional_invoice_payment_due';

    const InquiryReceived                      = 'inquiry_received';
    const InquiryAccepted                      = 'inquiry_accepted';
    const InquiryRejected                      = 'inquiry_rejected';

    const ComplaintReplied                     = 'complaint_replied';

    const AdApproved = 'ad_approved';
    const AdRejected = 'ad_rejected';

    const SettlementAccepted = 'settlement_accepted';
    const SettlementRejected = 'settlement_rejected';


    const EntityCreated = 'entity_created';
    const EntityApproved = 'entity_approved';
    const EntityRejected = 'entity_rejected';
    const EntityNeedsApproval = 'entity_needs_approval';

    const ContactNotify = 'contact_notify';
    const ReservationInProgress = 'reservation_in_progress';

    const WithdrawAccept = 'withdraw_accept';
    const WithdrawReject = 'withdraw_reject';

}
