<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Provider Categories Controller ----------*/
Route::get('provider-categories', [
    'uses'     => 'ProviderCategoryController@index',
    'as'       => 'provider-categories.index',
    'title'    => 'provider-categories.index',
    'sub_link' => true,
    'icon' => '<i class="feather icon-truck"></i>',
    'type' => 'parent',
    'has_sub_route' => true,
    'child' => [
        'provider-categories.index',
        'provider-categories.create',
        'provider-categories.store',
        'provider-categories.edit',
        'provider-categories.update',
        'provider-categories.show',
        'provider-categories.delete',
        'provider-categories.deleteAll',
    ],
]);

# provider-categories create
Route::get(
    'provider-categories/create',
    [
        'uses'  => 'ProviderCategoryController@create',
        'as'    => 'provider-categories.create',
        'title' => 'provider-categories.create_page',
        'sub_link' => true,
    ]
);

# store
Route::post('provider-categories/store', [
    'uses'  => 'ProviderCategoryController@store',
    'as'    => 'provider-categories.store',
    'title' => 'provider-categories.create',
]);

# provider-categories update
Route::get('provider-categories/{id}/edit', [
    'uses'  => 'ProviderCategoryController@edit',
    'as'    => 'provider-categories.edit',
    'title' => 'provider-categories.edit_page',
]);

# update
Route::put(
    'provider-categories/{id}',
    [
        'uses'  => 'ProviderCategoryController@update',
        'as'    => 'provider-categories.update',
        'title' => 'provider-categories.edit',
    ]
);

Route::get('provider-categories/{id}/show', [
    'uses'  => 'ProviderCategoryController@show',
    'as'    => 'provider-categories.show',
    'title' => 'provider-categories.show',
]);

# delete
Route::delete('provider-categories/{id}', [
    'uses'  => 'ProviderCategoryController@destroy',
    'as'    => 'provider-categories.delete',
    'title' => 'provider-categories.delete',
]);

# delete
Route::post('delete-all-provider-categories', [
    'uses'  => 'ProviderCategoryController@destroyAll',
    'as'    => 'provider-categories.deleteAll',
    'title' => 'provider-categories.delete_all',
]);
/*------------ end Of provider-categories Controller ----------*/

