<?php

use Illuminate\Support\Facades\Route;

Route::get('settings', [
    'uses'     => 'SettingController@index',
    'as'       => 'settings.index',
    'title'    => 'setting.index',
    'sub_link' => true,
]);

# update
Route::put('settings', [
    'uses'  => 'SettingController@update',
    'as'    => 'settings.update',
    'title' => 'setting.edit',
]);


/*------------ start Of notifications ----------*/
Route::get('notifications', [
    'uses'          => 'NotificationController@index',
    'as'            => 'notifications.index',
    'title'         => 'notifications.index',
    'icon'          => '<i class="ficon feather icon-bell"></i>',
    'type'          => 'parent',
    'has_sub_route' => false,
    'child'         => ['notifications.send'],
]);

Route::post('send-notifications', [
    'uses'  => 'NotificationController@sendNotifications',
    'as'    => 'notifications.send',
    'title' => 'notifications.send',
]);
/*------------ end Of notifications ----------*/

/*------------ start Of sms ----------*/
Route::get('sms', [
    'uses'          => 'SMSController@index',
    'as'            => 'sms.index',
    'title'         => 'message_packages.index',
    'icon'          => '<i class="feather icon-smartphone"></i>',
    // 'type'          => 'parent',
    'has_sub_route' => false,
    'child'         => ['sms.update', 'sms.change'],
]);

# sms change
Route::post('sms-change', [
    'uses'  => 'SMSController@change',
    'as'    => 'sms.change',
    'title' => 'message_packages.change',
]);

# sms update
Route::put('sms/{id}', [
    'uses'  => 'SMSController@update',
    'as'    => 'sms.update',
    'title' => 'message_packages.update',
]);

/*------------ end Of sms ----------*/

/*------------ start Of pages ----------*/
Route::get('pages', [
    'uses'      => 'PagesController@index',
    'as'        => 'pages.index',
    'title'     => 'pages.index',
    'icon'      => '<i class="feather icon-image"></i>',
    'type' => 'parent',
    'sub_link' => true,
    'child'     => ['pages.edit', 'pages.update', 'pages.show']
]);

# pages update
Route::get('pages/{id}/edit', [
    'uses'  => 'PagesController@edit',
    'as'    => 'pages.edit',
    'title' => 'pages.edit_page'
]);

# pages update
Route::put('pages/{id}', [
    'uses'  => 'PagesController@update',
    'as'    => 'pages.update',
    'title' => 'pages.edit'
]);

# pages show
Route::get('pages/{id}/Show', [
    'uses'  => 'PagesController@show',
    'as'    => 'pages.show',
    'title' => 'pages.show',
]);
/*------------ end Of pages ----------*/