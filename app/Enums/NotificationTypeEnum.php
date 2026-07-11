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
    const ContactReplied = 'contact_replied';

    const BookingNewReceived                 = 'booking_new_received';
    const BookingAccepted                    = 'booking_accepted';
    const BookingRejected                    = 'booking_rejected';
    const BookingPaid                        = 'booking_paid';
    const BookingCompleted                   = 'booking_completed';
    const BookingCancelled                   = 'booking_cancelled';
    const BookingAdditionalInvoiceIssued     = 'booking_additional_invoice_issued';
    const BookingAdditionalInvoicePaymentDue = 'booking_additional_invoice_payment_due';

    const InquiryReceived  = 'inquiry_received';
    const InquiryAccepted  = 'inquiry_accepted';
    const InquiryRejected  = 'inquiry_rejected';
    const ComplaintReplied = 'complaint_replied';

    const AdApproved = 'ad_approved';
    const AdRejected = 'ad_rejected';

    const EntityCreated       = 'entity_created';
    const EntityApproved      = 'entity_approved';
    const EntityRejected      = 'entity_rejected';
    const EntityNeedsApproval = 'entity_needs_approval';

    const ContactNotify = 'contact_notify';

}
