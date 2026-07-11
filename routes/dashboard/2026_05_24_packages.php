<?php

use Illuminate\Support\Facades\Route;

/*------------ start Of Packages Controller ----------*/
Route::get('packages', [
    'uses'          => 'PackageController@index',
    'as'            => 'packages.index',
    'title'         => 'packages.index',
    'sub_link'      => true,
    'icon'          => '<i class="feather icon-package"></i>',
    'type'          => 'parent',
    'has_sub_route' => true,
    'child'         => [
        'packages.index',
        'packages.create',
        'packages.store',
        'packages.edit',
        'packages.update',
        'packages.show',
        'packages.delete',
        'packages.deleteAll',
    ],
]);

Route::get('packages/create', [
    'uses'     => 'PackageController@create',
    'as'       => 'packages.create',
    'title'    => 'packages.create_page',
    'sub_link' => true,
]);

Route::post('packages/store', [
    'uses'  => 'PackageController@store',
    'as'    => 'packages.store',
    'title' => 'packages.create',
]);

Route::get('packages/{id}/edit', [
    'uses'  => 'PackageController@edit',
    'as'    => 'packages.edit',
    'title' => 'packages.edit_page',
]);

Route::put('packages/{id}', [
    'uses'  => 'PackageController@update',
    'as'    => 'packages.update',
    'title' => 'packages.edit',
]);

Route::get('packages/{id}/show', [
    'uses'  => 'PackageController@show',
    'as'    => 'packages.show',
    'title' => 'packages.show',
]);

Route::delete('packages/{id}', [
    'uses'  => 'PackageController@destroy',
    'as'    => 'packages.delete',
    'title' => 'packages.delete',
]);

Route::post('delete-all-packages', [
    'uses'  => 'PackageController@destroyAll',
    'as'    => 'packages.deleteAll',
    'title' => 'packages.delete_all',
]);
/*------------ end Of Packages Controller ----------*/
