<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CountriesAndCitiesController;
use App\Http\Controllers\Api\ExportPdfController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\StaticController;

$prefixes = [
    'client',
    'provider',
];


foreach ($prefixes as $prefix) {

    $capitalPrefix = ucfirst($prefix);

    Route::prefix($prefix)->group(function () use ($capitalPrefix, $prefix) {

        // forgot password
        Route::post('password/forget', "$capitalPrefix\ForgetPasswordController@forgetPassword");
        Route::post('password/check-code', "$capitalPrefix\ForgetPasswordController@checkCode");
        Route::post('password/reset', "$capitalPrefix\ForgetPasswordController@resetPassword");
        // forgot password

        // authentication
        Route::post('register', "$capitalPrefix\AuthController@register");
        Route::put('activate', "$capitalPrefix\AuthController@activate");
        Route::post('resend-code', "$capitalPrefix\AuthController@resendCode");
        Route::post('login', "$capitalPrefix\AuthController@login");
        // authentication

        // contacts
        Route::middleware(['auth:sanctum'])->group(function () use ($capitalPrefix) {
            Route::post('contact', "$capitalPrefix\ProfileController@contact");
            Route::post('profile/complete', "$capitalPrefix\ProfileController@completeProfile");
        });
        // contacts

        Route::middleware(['auth:sanctum', 'is-active'])->group(function () use ($capitalPrefix, $prefix) {


            // authentication
            Route::post('logout', "$capitalPrefix\AuthController@logout");
            Route::delete('delete-account', "$capitalPrefix\AuthController@deleteAccount");
            // authentication

            // profile
            Route::get('profile', "$capitalPrefix\ProfileController@getProfile");
            Route::patch('change-lang', "$capitalPrefix\ProfileController@updateLanguage");
            if ($prefix === 'provider') {
                Route::patch('change-location', "$capitalPrefix\ProfileController@updateLocation");
            }
            Route::post('update/profile', "$capitalPrefix\ProfileController@update");
            Route::post('check-password', "$capitalPrefix\ProfileController@checkPassword");
            Route::patch('update-password', "$capitalPrefix\ProfileController@updatePassword");
            // profile


            // update phone
            Route::post('send-code-to-old-phone', "$capitalPrefix\ProfileController@sendCodeToOldPhone");
            Route::post('resend-code-to-old-phone', "$capitalPrefix\ProfileController@sendCodeToOldPhone");
            Route::post('verify-old-phone', "$capitalPrefix\ProfileController@verifyOldPhone");
            Route::post('send-code-to-new-phone', "$capitalPrefix\ProfileController@sendCodeToNewPhone");
            Route::post('resend-code-to-new-phone', "$capitalPrefix\ProfileController@sendCodeToNewPhone");
            Route::post('verify-new-phone', "$capitalPrefix\ProfileController@verifyNewPhone");
            // update phone


            // update email (provider only — client has no email)
            if ($prefix === 'provider') {
                Route::post('email/update', "$capitalPrefix\UpdateEmailController@changeEmail");
                Route::post('email/verify', "$capitalPrefix\UpdateEmailController@verifyEmail");
            }

            // notifications
            Route::patch('switch-notify', "$capitalPrefix\NotificationController@switchNotificationStatus");
            Route::get('notifications', "$capitalPrefix\NotificationController@getNotifications");
            Route::get('count-notifications', "$capitalPrefix\NotificationController@countUnreadNotifications");
            Route::delete('delete-notification/{notification_id}', "$capitalPrefix\NotificationController@deleteNotification");
            Route::delete('delete-notifications', "$capitalPrefix\NotificationController@deleteNotifications");
            // notifications

        });
    });
}

Route::middleware(['optional.auth'])->group(function () {

    Route::get('settings', [SettingController::class, 'settings']);
    Route::get('is-production', [SettingController::class, 'isProduction']);
    Route::get('countries-with-cities', [CountriesAndCitiesController::class, 'countriesWithCities']);
    Route::get('country/{country_id}/cities', [CountriesAndCitiesController::class, 'CountryCities']);

    // contacts
    Route::get('contact', [SettingController::class, 'contact']);
    Route::get('main', [SettingController::class, 'main']);
    Route::get('who-is-us', [SettingController::class, 'whoIsUs']);


    // Static Page
    Route::get('about', [StaticController::class, 'aboutUs']);
    Route::get('terms', [StaticController::class, 'terms']);
    Route::get('privacy', [StaticController::class, 'privacy']);
    Route::get('socials', [SettingController::class, 'socials']);

    // global endpoints
    Route::get('countries', [CountriesAndCitiesController::class, 'countries']);
    Route::get('cities', [CountriesAndCitiesController::class, 'cities']);

    // store contact
    Route::post('contact/store', [ContactController::class, 'contact']);

    Route::get('export-pdf', [ExportPdfController::class, 'export']);
});
