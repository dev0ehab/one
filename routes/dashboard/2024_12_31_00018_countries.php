<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Countries Controller ----------*/
Route::get('countries', [
    'uses'     => 'CountryController@index',
    'as'       => 'countries.index',
    'title'    => 'countries.index',
    'sub_link' => true,
    'icon' => '<i class="feather icon-flag"></i>',
    'type' => 'parent',
    'has_sub_route' => true,
    'child' => [
        'countries.index',
        'countries.create',
        'countries.store',
        'countries.edit',
        'countries.update',
        'countries.show',
        'countries.delete',
        'countries.deleteAll',
    ],
]);

# countries create
Route::get(
    'countries/create',
    [
        'uses'  => 'CountryController@create',
        'as'    => 'countries.create',
        'title' => 'countries.create_page',
        'sub_link' => true,
    ]
);

# store
Route::post('countries/store', [
    'uses'  => 'CountryController@store',
    'as'    => 'countries.store',
    'title' => 'countries.create',
]);

# countries update
Route::get('countries/{id}/edit', [
    'uses'  => 'CountryController@edit',
    'as'    => 'countries.edit',
    'title' => 'countries.edit_page',
]);

# update
Route::put(
    'countries/{id}',
    [
        'uses'  => 'CountryController@update',
        'as'    => 'countries.update',
        'title' => 'countries.edit',
    ]
);

Route::get('countries/{id}/show', [
    'uses'  => 'CountryController@show',
    'as'    => 'countries.show',
    'title' => 'countries.show',
]);

# delete
Route::delete('countries/{id}', [
    'uses'  => 'CountryController@destroy',
    'as'    => 'countries.delete',
    'title' => 'countries.delete',
]);

# delete
Route::post('delete-all-countries', [
    'uses'  => 'CountryController@destroyAll',
    'as'    => 'countries.deleteAll',
    'title' => 'countries.delete_all',
]);
/*------------ end Of countries Controller ----------*/
