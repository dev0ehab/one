<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Provider Update Requests (طلبات تعديل البروفايدر) ----------*/
Route::get('provider-update-requests', [
    'uses'     => 'EntityUpdateController@indexProvider',
    'as'       => 'provider_update_requests.index',
    'sub_link' => true,
    'title'    => 'provider_update_requests.index',
]);

Route::get('provider-update-requests/{id}/show', [
    'uses'  => 'EntityUpdateController@showProvider',
    'as'    => 'provider_update_requests.show',
    'title' => 'provider_update_requests.show',
]);

Route::post('provider-update-requests/{id}/approve', [
    'uses'  => 'EntityUpdateController@approveProvider',
    'as'    => 'provider_update_requests.approve',
    'title' => 'provider_update_requests.approve',
]);

Route::post('provider-update-requests/{id}/reject', [
    'uses'  => 'EntityUpdateController@rejectProvider',
    'as'    => 'provider_update_requests.reject',
    'title' => 'provider_update_requests.reject',
]);
/*------------ end Of Provider Update Requests ----------*/

