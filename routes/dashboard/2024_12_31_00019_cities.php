<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Cities Controller ----------*/
Route::get('cities', [
    'uses'     => 'CityController@index',
    'as'       => 'cities.index',
    'title'    => 'cities.index',
    'sub_link' => true,
    'icon' => '<i class="feather icon-map-pin"></i>',
    'type' => 'parent',
    'has_sub_route' => true,
    'child' => [
        'cities.index',
        'cities.create',
        'cities.store',
        'cities.edit',
        'cities.update',
        'cities.show',
        'cities.delete',
        'cities.deleteAll',
    ],
]);

# cities create
Route::get(
    'cities/create',
    [
        'uses'  => 'CityController@create',
        'as'    => 'cities.create',
        'title' => 'cities.create_page',
        'sub_link' => true,
    ]
);

# store
Route::post('cities/store', [
    'uses'  => 'CityController@store',
    'as'    => 'cities.store',
    'title' => 'cities.create',
]);

# cities update
Route::get('cities/{id}/edit', [
    'uses'  => 'CityController@edit',
    'as'    => 'cities.edit',
    'title' => 'cities.edit_page',
]);

# update
Route::put(
    'cities/{id}',
    [
        'uses'  => 'CityController@update',
        'as'    => 'cities.update',
        'title' => 'cities.edit',
    ]
);

Route::get('cities/{id}/show', [
    'uses'  => 'CityController@show',
    'as'    => 'cities.show',
    'title' => 'cities.show',
]);

# delete
Route::delete('cities/{id}', [
    'uses'  => 'CityController@destroy',
    'as'    => 'cities.delete',
    'title' => 'cities.delete',
]);

# delete
Route::post('delete-all-cities', [
    'uses'  => 'CityController@destroyAll',
    'as'    => 'cities.deleteAll',
    'title' => 'cities.delete_all',
]);
/*------------ end Of cities Controller ----------*/
