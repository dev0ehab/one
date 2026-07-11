<?php

use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\Provider\{
    AdController,
    BankAccountController,
    BookingController,
    BookingServiceController,
    ChatController,
    ComplainController,
    HomeController,
    InquiryController,
    PackageController,
    RateController,
    WorkDaysController,
};
use App\Http\Controllers\Api\StaticController;
use Illuminate\Support\Facades\Route;

Route::prefix('provider')->group(function () {

    Route::middleware('optional.auth')->group(function () {
        Route::get('app-intro', [StaticController::class, 'providerAppIntro']);
        Route::get('terms', [StaticController::class, 'terms']);
    });

    Route::middleware(['auth:sanctum', 'check-auth-type:provider'])->group(function () {

        Route::get('home', [HomeController::class, 'index']);

        // bookings
        Route::get('bookings', [BookingController::class, 'index']);
        Route::post('bookings/{id}/accept', [BookingController::class, 'accept']);
        Route::post('bookings/{id}/reject', [BookingController::class, 'reject']);
        Route::post('bookings/{id}/additional-invoice', [BookingController::class, 'issueAdditionalInvoice']);
        Route::post('bookings/{id}/complete', [BookingController::class, 'complete']);

        // wallet
        Route::get('wallet', [WalletController::class, 'show']);
        Route::get('wallet/transactions', [WalletController::class, 'transactions']);
        Route::post('wallet/charge', [WalletController::class, 'charge']);
        Route::post('wallet/withdraw', [WalletController::class, 'withdraw']);

        // bank account (for withdrawals)
        Route::get('bank-account', [BankAccountController::class, 'show']);
        Route::put('bank-account', [BankAccountController::class, 'update']);

        // booking service subscription & settings
        Route::get('booking-service', [BookingServiceController::class, 'show']);
        Route::post('booking-service/subscribe', [BookingServiceController::class, 'subscribe']);
        Route::post('booking-service', [BookingServiceController::class, 'update']);

        // work days
        Route::get('work-days', [WorkDaysController::class, 'index']);
        Route::put('work-days/update-work-days', [WorkDaysController::class, 'updateWorkDays']);

        // packages & subscription
        Route::get('packages', [PackageController::class, 'index']);
        Route::post('packages/{id}/subscribe', [PackageController::class, 'subscribe']);
        Route::get('subscription', [PackageController::class, 'currentSubscription']);

        // ads
        Route::get('ads', [AdController::class, 'index']);
        Route::post('ads', [AdController::class, 'store']);
        Route::get('ads/{id}', [AdController::class, 'show']);
        Route::delete('ads/{id}', [AdController::class, 'destroy']);

        // chats
        Route::post('chats/start', [ChatController::class, 'start']);
        Route::get('chats', [ChatController::class, 'index']);
        Route::get('chats/{id}', [ChatController::class, 'show']);

        // inquiries
        Route::get('inquiries', [InquiryController::class, 'index']);
        Route::get('inquiries/{id}', [InquiryController::class, 'show']);
        Route::post('inquiries/{id}/accept', [InquiryController::class, 'accept']);
        Route::post('inquiries/{id}/reject', [InquiryController::class, 'reject']);

        // complains
        Route::get('complains', [ComplainController::class, 'index']);
        Route::post('complains', [ComplainController::class, 'store']);
        Route::get('complains/{id}', [ComplainController::class, 'show']);

        // rates
        Route::get('rates', [RateController::class, 'index']);
    });
});
