<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Complains Controller ----------*/
Route::get('complains/{client_id?}', [
    'uses'     => 'ComplainController@index',
    'as'       => 'complains.index',
    'title'    => 'complains.index',
    'sub_link' => true,
    'icon' => '<i class="feather icon-message-circle"></i>',
    'type' => 'parent',
    'has_sub_route' => true,
    'child' => [
        'complains.index',
        'complains.show',
        'complains.processing',
        'complains.completed',
        'complains.deleteAll',
        'complains.destroy',
    ],
]);

Route::get('complains/{id}/show', [
    'uses'  => 'ComplainController@show',
    'as'    => 'complains.show',
    'title' => 'complains.show',
]);

Route::post('complains/{id}/processing', [
    'uses'  => 'ComplainController@processing',
    'as'    => 'complains.processing',
    'title' => 'complains.processing',
]);

Route::post('complains/{id}/completed', [
    'uses'  => 'ComplainController@completed',
    'as'    => 'complains.completed',
    'title' => 'complains.completed',
]);

Route::post('complains/deleteAll', [
    'uses'  => 'ComplainController@destroyAll',
    'as'    => 'complains.deleteAll',
    'title' => 'complains.delete_all',
]);

Route::delete('complains/{id}', [
    'uses'  => 'ComplainController@destroy',
    'as'    => 'complains.destroy',
    'title' => 'complains.delete',
]);
/*------------ end Of Complains Controller ----------*/

