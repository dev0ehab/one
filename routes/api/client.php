<?php

use App\Http\Controllers\Api\WalletController;

use App\Http\Controllers\Api\Client\{
    AdController,
    BookingController,
    ChatController,
    ComplainController,
    FavoriteController,
    HomeController,
    InquiryController,
    ProviderController,
};
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StaticController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function () {
    Route::middleware('optional.auth')->group(function () {

        // home
        Route::get('home', [HomeController::class, 'index']);

        // ads (approved active ads from providers)
        Route::get('ads', [AdController::class, 'index']);
        Route::get('ads/{id}', [AdController::class, 'show']);

        // providers
        Route::get('providers', [ProviderController::class, 'index']);
        Route::get('providers/map', [ProviderController::class, 'map']);
        Route::get('providers/categories', [ProviderController::class, 'getCategories']);
        Route::get('providers/{id}/reviews', [ProviderController::class, 'reviews']);
        Route::get('providers/{id}/ads', [ProviderController::class, 'ads']);
        Route::get('providers/{id}/booking-slots', [BookingController::class, 'availability']);
        Route::get('providers/{id}', [ProviderController::class, 'show']);

        // Static Page
        Route::get('about', [StaticController::class, 'aboutUs']);
        Route::get('terms', [StaticController::class, 'terms']);
        Route::get('privacy', [StaticController::class, 'privacy']);
        Route::get('socials', [SettingController::class, 'socials']);
        Route::get('app-intro', [StaticController::class, 'clientAppIntro']);

    });

    Route::middleware(['auth:sanctum', 'check-auth-type:client'])->group(function () {

        // home — today's schedule
        Route::get('home/schedule', [HomeController::class, 'schedule']);

        // favorites
        Route::get('favorites', [FavoriteController::class, 'index']);
        Route::post('favorites/toggle', [FavoriteController::class, 'toggle']);

        // complains (Figma more: submit + view status/reply — no client update/delete)
        Route::get('complains', [ComplainController::class, 'index']);
        Route::post('complains', [ComplainController::class, 'store']);
        Route::get('complains/{id}', [ComplainController::class, 'show']);

        // bookings (Figma: حجوزاتي)
        Route::get('bookings', [BookingController::class, 'index']);
        Route::post('bookings', [BookingController::class, 'store']);
        Route::post('bookings/dismiss-empty-banner', [BookingController::class, 'dismissEmptyBanner']);
        Route::get('bookings/{id}', [BookingController::class, 'show']);
        Route::post('bookings/{id}/pay', [BookingController::class, 'pay']);
        Route::post('bookings/{id}/cancel', [BookingController::class, 'cancel']);
        Route::post('bookings/{id}/rate', [BookingController::class, 'rate']);
        Route::post('bookings/{id}/additional-invoice/accept', [BookingController::class, 'acceptAdditionalInvoice']);
        Route::post('bookings/{id}/additional-invoice/reject', [BookingController::class, 'rejectAdditionalInvoice']);
        Route::post('bookings/{id}/additional-invoice/pay', [BookingController::class, 'payAdditionalInvoice']);

        // chats
        Route::post('chats/start', [ChatController::class, 'start']);
        Route::get('chats', [ChatController::class, 'index']);
        Route::get('chats/{id}', [ChatController::class, 'show']);

        // inquiries (Figma: استفسارات)
        Route::get('inquiries', [InquiryController::class, 'index']);
        Route::post('inquiries', [InquiryController::class, 'store']);
        Route::get('inquiries/{id}', [InquiryController::class, 'show']);
        Route::post('inquiries/{id}/cancel', [InquiryController::class, 'cancel']);

        // Wallet
        Route::get('wallet', [WalletController::class, 'show']);
        Route::get('wallet/transactions', [WalletController::class, 'transactions']);
        Route::post('wallet/charge', [WalletController::class, 'charge']);

    
    });
});
