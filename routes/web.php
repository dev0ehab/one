<?php

use Illuminate\Support\Facades\Route;

// Set Language For the admin
Route::get('/lang/{lang}', 'AuthController@SetLanguage');

// Guest routes for admin
Route::middleware('guest:admin')->group(function () {
    Route::get('login', 'AuthController@showLoginForm')->name('show.login');
    Route::post('login', 'AuthController@login')->name('login');
});


Route::get('logout', 'AuthController@logout')->name('logout');


Route::middleware('admin')->group(function () {

    /*------------ start Of Dashboard----------*/
    Route::get('dashboard', [
        'uses' => 'HomeController@dashboard',
        'as' => 'dashboard',
        'icon' => '<i class="feather icon-home"></i>',
        'title' => 'main_page',
        'has_sub_route' => false,
        'type' => 'parent',
    ]);
    /*------------ end Of dashboard ----------*/



    Route::get('dashboard-users', [
        'as' => 'dashboard_users',
        'icon' => '<i class="feather icon-user-check"></i>',
        'title' => 'dashboard_users',
        'type' => 'parent',
        'has_sub_route' => true,
        'child' => [
            'admins.index',
            'admins.show',
            'admins.create',
            'admins.store',
            'admins.edit',
            'admins.update',
            'admins.delete',
            'admins.deleteAll',
            // 'admins.notifications',
            // 'admins.notifications.delete',

            'clients.index',
            'clients.show',
            'clients.create',
            'clients.store',
            'clients.edit',
            'clients.update',
            'clients.delete',
            'clients.deleteAll',
            'clients.notify',
            'clients.updateBalance',
            'clients.block',
            'clients.toggleLanding',

            'providers.index',
            'providers.show',
            'providers.create',
            'providers.store',
            'providers.edit',
            'providers.update',
            'providers.delete',
            'providers.deleteAll',
            'providers.notify',
            'providers.block',

        ],
    ]);

    // Route::get('intro-sites', [
    //     'uses' => 'IntroSiteController@index',
    //     'as' => 'intro_sites',
    //     'icon' => '<i class="feather icon-user-check"></i>',
    //     'title' => 'intro_sites',
    //     'type' => 'parent',
    // ]);

    // Route::put('intro-sites', [
    //     'uses' => 'IntroSiteController@update',
    //     'as' => 'intro_sites.update',
    //     'title' => 'intro_sites.update',
    // ]);

    Route::get('export/{export}', 'ExcelController@master')->name('master-export');
    Route::post('import-items', 'ExcelController@importItems')->name('import-items');

    Route::resource('app_intros', 'AppIntroController');

    /*------------ start Of Modification Requests (طلبات تعديل) ----------*/
    Route::get('modification-requests', [
        'as' => 'modification_requests',
        'icon' => '<i class="feather icon-edit"></i>',
        'title' => 'modification_requests',
        'type' => 'parent',
        'has_sub_route' => true,
        'child' => [
            'provider_update_requests.index',
            'provider_update_requests.show',
            'provider_update_requests.approve',
            'provider_update_requests.reject',
        ],
    ]);
    /*------------ end Of Modification Requests ----------*/

    #new_routes_here

});

// redirect to home if url not found
Route::fallback(function () {
    return redirect()->route('admin.show.login');
});
