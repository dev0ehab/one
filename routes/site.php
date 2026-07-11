<?php

use App\Http\Controllers\Admin\AuthController;
use App\Services\PaymentGateway\PaymentService;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->get('/', [AuthController::class, 'showLoginForm'])->name('intro');
Route::get('/'                    , 'IntroController@index')->name('intro');
Route::get('/clients-more'          , 'IntroController@clientsMore')->name('clientsMore');
Route::get('/privacy-policy'      , 'IntroController@privacyPolicy')->name('IntroPrivacyPolicy');
Route::get('/delete-account-page' , 'IntroController@deleteAccount')->name('deleteAccountPage');
Route::post('/send-message'       , 'IntroController@sendMessage');
Route::post('/subscribe-email'    , 'IntroController@subscribe')->name('subscribe-email');
Route::get('/lang/{lang}'         , 'IntroController@SetLanguage');
Route::get('/get-articles'         , 'IntroController@getArticles');
Route::get('/get-article-details/{id}', 'IntroController@getArticleDetails');






Route::post('/send-message', 'IntroController@sendMessage');
Route::get('/lang/{lang}', 'IntroController@SetLanguage');

Route::group(['middleware' => ['guest']], function () { });

Route::get('/hyper-pay-form/{transaction_id}/{brand_id}/{brand_type}', 'PaymentController@getHyperPay')
    ->name('getHyperPay');

Route::group(['middleware' => ['auth']], function () {
    // Route::get('/show-chat/{id}', 'ChatController@getChatRoom')->name('getChatRoom');
    // Route::post('/upload-chat-file', 'ChatController@uploadChatFile')->name('uploadChatFile');
});

// API documentation
Route::get('/docs/booking', function () {
    return response(file_get_contents(base_path('docs/booking_full_cycle.html')))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('docs.booking');

// redirect to home if url not found
Route::fallback(function () {
    return redirect()->route('intro');
});

// payment
Route::get('payment/get-payment-status/{brand_id?}', [PaymentService::class, 'callback'])
    ->name('payment.getPaymentStatus');
// payment

// payment status redirect
Route::get('payment/{transaction_id}/{status}', function ($transaction_id, $status) {
    return view('payment.' . $status, [
        'trans_id' => $transaction_id,
        'status' => $status == 'success' ? 1 : 0
    ]);
})->name('payment.status.redirect');
// payment status redirect
// payment
