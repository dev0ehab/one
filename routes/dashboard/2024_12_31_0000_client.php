<?php

use Illuminate\Support\Facades\Route;

Route::get('clients', [
    'uses'     => 'ClientController@index',
    'as'       => 'clients.index',
    'sub_link' => true,
    'title'    => 'users.index',
]);

# clients store
Route::get('clients/create', [
    'uses'  => 'ClientController@create',
    'as'    => 'clients.create',
    'title' => 'users.create_page',
]);

# clients update
Route::get('clients/{id}/edit', [
    'uses'  => 'ClientController@edit',
    'as'    => 'clients.edit',
    'title' => 'users.edit_page',
]);

# store
Route::post('clients/store', [
    'uses'  => 'ClientController@store',
    'as'    => 'clients.store',
    'title' => 'users.create',
]);

# update
Route::put('clients/{id}', [
    'uses'  => 'ClientController@update',
    'as'    => 'clients.update',
    'title' => 'users.edit',
]);

Route::get('clients/{id}/show', [
    'uses'  => 'ClientController@show',
    'as'    => 'clients.show',
    'title' => 'users.show',
]);

# delete
Route::delete('clients/{id}', [
    'uses'  => 'ClientController@destroy',
    'as'    => 'clients.delete',
    'title' => 'users.delete',
]);

# delete
Route::post('delete-all-clients', [
    'uses'  => 'ClientController@destroyAll',
    'as'    => 'clients.deleteAll',
    'title' => 'users.delete_all',
]);

# notify
Route::post('admins/clients/notify', [
    'uses'  => 'ClientController@notify',
    'as'    => 'clients.notify',
    'title' => 'users.send_notification',
]);

Route::post('admins/clients/update-balance/{id}', [
    'uses'  => 'ClientController@updateBalance',
    'as'    => 'clients.updateBalance',
    'title' => 'users.updateBalance',
]);

Route::post('admins/clients/block-user', [
    'uses'  => 'ClientController@block',
    'as'    => 'clients.block',
    'title' => 'users.block_user',
]);

Route::post('admins/clients/toggle-landing', [
    'uses'  => 'ClientController@toggleLanding',
    'as'    => 'clients.toggleLanding',
    'title' => 'users.toggle_landing',
]);

Route::post('admins/clients/update-points/{id}', [
    'uses'  => 'ClientController@updatePoints',
    'as'    => 'clients.updatePoints',
    'title' => 'users.updatePoints',
]);