<?php

use App\Http\Controllers\Site\IntroController;
use Illuminate\Support\Facades\Route;

Route::prefix('site')->group(function () {
    Route::get('home', [IntroController::class, 'index']);
    Route::get('clients', [IntroController::class, 'getAllClients']);
    Route::get('providers', [IntroController::class, 'getAllproviders']);
    Route::post('contact-us', [IntroController::class, 'contactUs'])->middleware('throttle:5,1')->name('site.contactUs');
    Route::post('newsletter', [IntroController::class, 'newsletter']);
});