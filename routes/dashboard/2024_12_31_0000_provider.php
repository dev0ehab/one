<?php

use Illuminate\Support\Facades\Route;

Route::get('providers', [
    'uses'     => 'ProviderController@index',
    'as'       => 'providers.index',
    'sub_link' => true,
    'title'    => 'providers.index',
]);

# providers store
Route::get('providers/create', [
    'uses'  => 'ProviderController@create',
    'as'    => 'providers.create',
    'title' => 'providers.create_page',
]);

# providers update
Route::get('providers/{id}/edit', [
    'uses'  => 'ProviderController@edit',
    'as'    => 'providers.edit',
    'title' => 'providers.edit_page',
]);

# store
Route::post('providers/store', [
    'uses'  => 'ProviderController@store',
    'as'    => 'providers.store',
    'title' => 'providers.create',
]);

# update
Route::put('providers/{id}', [
    'uses'  => 'ProviderController@update',
    'as'    => 'providers.update',
    'title' => 'providers.edit',
]);

Route::get('providers/{id}/show', [
    'uses'  => 'ProviderController@show',
    'as'    => 'providers.show',
    'title' => 'providers.show',
]);

Route::get('providers-contacts/{id}/show', [
    'uses'  => 'ProviderController@contacts',
    'as'    => 'providers.contacts.show',
    'title' => 'providers.contacts',
]);

# delete
Route::delete('providers/{id}', [
    'uses'  => 'ProviderController@destroy',
    'as'    => 'providers.delete',
    'title' => 'providers.delete',
]);

# delete
Route::post('delete-all-providers', [
    'uses'  => 'ProviderController@destroyAll',
    'as'    => 'providers.deleteAll',
    'title' => 'providers.delete_all',
]);

# notify
Route::post('admins/providers/notify', [
    'uses'  => 'ProviderController@notify',
    'as'    => 'providers.notify',
    'title' => 'providers.send_notification',
]);


Route::post('admins/providers/block-user', [
    'uses'  => 'ProviderController@block',
    'as'    => 'providers.block',
    'title' => 'providers.block_user',
]);

Route::get('get-cities-by-country', [
    'uses'  => 'ProviderController@getCitiesByCountry',
    'as'    => 'get.cities.by.country',
    'title' => 'Get Cities By Country',
]);

Route::post('providers/{provider}/store-work-day', [
    'uses'  => 'ProviderController@storeWorkDay',
    'as'    => 'providers.storeWorkDay',
    'title' => 'Store Work Day',
]);

Route::post('admins/providers/accept', [
    'uses'  => 'ProviderController@accept',
    'as'    => 'providers.accept',
    'title' => 'providers.accept',
]);

Route::post('admins/providers/reject', [
    'uses'  => 'ProviderController@reject',
    'as'    => 'providers.reject',
    'title' => 'providers.reject',
]);

Route::get('providers/{provider}/show-room', [
    'uses'  => 'ProviderController@showRoom',
    'as'    => 'providers.showRoom',
    'title' => 'providers.show_room',
]);
