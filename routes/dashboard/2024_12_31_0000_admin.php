<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of profile----------*/
Route::get('profile', [
    'uses'  => 'HomeController@profile',
    'as'    => 'profile.profile',
    'title' => 'profile',
]);

Route::put('profile-update', [
    'uses'  => 'HomeController@updateProfile',
    'as'    => 'profile.update',
    'title' => 'update_profile',
]);

Route::put('profile-update-password', [
    'uses'  => 'HomeController@updatePassword',
    'as'    => 'profile.update_password',
    'title' => 'update_password',
]);
/*------------ end Of profile----------*/

/*------------ start Of Admins Controller ----------*/
Route::get('admins', [
    'uses'     => 'AdminController@index',
    'as'       => 'admins.index',
    'title'    => 'admins.index',
    'sub_link' => true,
]);

# admins store
Route::get('show-notifications', [
    'uses'  => 'AdminController@notifications',
    'as'    => 'admins.notifications',
    'title' => 'admins.notification_page',
]);

# admins store
Route::post('delete-notifications', [
    'uses'  => 'AdminController@deleteNotifications',
    'as'    => 'admins.notifications.delete',
    'title' => 'admins.delete_notification',
]);

# admins store
Route::get(
    'admins/create',
    [
        'uses'  => 'AdminController@create',
        'as'    => 'admins.create',
        'title' => 'admins.create_page',
    ]
);

# store
Route::post('admins/store', [
    'uses'  => 'AdminController@store',
    'as'    => 'admins.store',
    'title' => 'admins.create',
]);

# admins update
Route::get('admins/{id}/edit', [
    'uses'  => 'AdminController@edit',
    'as'    => 'admins.edit',
    'title' => 'admins.edit_page',
]);

# update
Route::put(
    'admins/{id}',
    [
        'uses'  => 'AdminController@update',
        'as'    => 'admins.update',
        'title' => 'admins.edit',
    ]
);

Route::get('admins/{id}/show', [
    'uses'  => 'AdminController@show',
    'as'    => 'admins.show',
    'title' => 'admins.show',
]);

# delete
Route::delete('admins/{id}', [
    'uses'  => 'AdminController@destroy',
    'as'    => 'admins.delete',
    'title' => 'admins.delete',
]);

# delete
Route::post('delete-all-admins', [
    'uses'  => 'AdminController@destroyAll',
    'as'    => 'admins.deleteAll',
    'title' => 'admins.delete_all',
]);

# block
Route::post('admins/block', [
    'uses'  => 'AdminController@block',
    'as'    => 'admins.block',
    'title' => 'admins.block',
]);
/*------------ end Of admins Controller ----------*/
