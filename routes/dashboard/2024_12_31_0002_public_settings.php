<?php

use Illuminate\Support\Facades\Route;

Route::get('public-settings', [
    'as'            => 'public-settings',
    'icon'          => '<i class="feather icon-sliders"></i>',
    'title'         => 'public_settings',
    'type'          => 'parent',
    'has_sub_route' => true,
    'child'         => [

        'socials.index',
        'socials.create',
        'socials.store',
        'socials.show',
        'socials.update',
        'socials.edit',
        'socials.delete',
        'socials.deleteAll',


        'all_contacts',
        'contacts.reply',
        'contacts.show',
        'contacts.delete',
        'contacts.deleteAll',


        'roles.index',
        'roles.create',
        'roles.store',
        'roles.edit',
        'roles.update',
        'roles.delete',

        'settings.index',
        'settings.update',
    ],
]);

/*------------ start Of socials ----------*/
Route::get('socials', [
    'uses'     => 'SocialController@index',
    'as'       => 'socials.index',
    'title'    => 'socials.index',
    'sub_link' => true,
]);

# socials update
Route::get('socials/{id}/Show', [
    'uses'  => 'SocialController@show',
    'as'    => 'socials.show',
    'title' => 'socials.show',
]);

# socials store
Route::get(
    'socials/create',
    [
        'uses'  => 'SocialController@create',
        'as'    => 'socials.create',
        'title' => 'socials.create_page',
    ]
);

# socials store
Route::post('socials', [
    'uses'  => 'SocialController@store',
    'as'    => 'socials.store',
    'title' => 'socials.create',
]);

# socials update
Route::get('socials/{id}/edit', [
    'uses'  => 'SocialController@edit',
    'as'    => 'socials.edit',
    'title' => 'socials.edit_page',
]);

# socials update
Route::put('socials/{id}', [
    'uses'  => 'SocialController@update',
    'as'    => 'socials.update',
    'title' => 'socials.edit',
]);

# socials delete
Route::delete('socials/{id}', [
    'uses'  => 'SocialController@destroy',
    'as'    => 'socials.delete',
    'title' => 'socials.delete',
]);

# delete all socials
Route::post('delete-all-socials', [
    'uses'  => 'SocialController@destroyAll',
    'as'    => 'socials.deleteAll',
    'title' => 'socials.delete_all',
]);
/*------------ end Of socials ----------*/

/*------------ start Of seos ----------*/
// Route::get('seos', [
//     'uses'     => 'SeoController@index',
//     'as'       => 'seos.index',
//     'title'    => 'seo.index',
//     'sub_link' => true,
// ]);

// # seos update
// Route::get('seos/{id}/Show', [
//     'uses'  => 'SeoController@show',
//     'as'    => 'seos.show',
//     'title' => 'seo.show',
// ]);

// # seos store
// Route::get('seos/create', [
//     'uses'  => 'SeoController@create',
//     'as'    => 'seos.create',
//     'title' => 'seo.create_page',
// ]);

// # seos update
// Route::get(
//     'seos/{id}/edit',
//     [
//         'uses'  => 'SeoController@edit',
//         'as'    => 'seos.edit',
//         'title' => 'seo.edit_page',
//     ]
// );

// # store
// Route::post(
//     'seos/store',
//     [
//         'uses'  => 'SeoController@store',
//         'as'    => 'seos.store',
//         'title' => 'seo.create',
//     ]
// );

// # update
// Route::put(
//     'seos/{id}',
//     [
//         'uses'  => 'SeoController@update',
//         'as'    => 'seos.update',
//         'title' => 'seo.edit',
//     ]
// );

// # delete
// Route::delete('seos/{id}', [
//     'uses'  => 'SeoController@destroy',
//     'as'    => 'seos.delete',
//     'title' => 'seo.delete',
// ]);

// # delete
// Route::post('delete-all-seos', [
//     'uses'  => 'SeoController@destroyAll',
//     'as'    => 'seos.deleteAll',
//     'title' => 'seo.delete_all',
// ]);
/*------------ end Of seos ----------*/

/*------------ start Of Roles----------*/
Route::get('roles', [
    'uses'     => 'RoleController@index',
    'as'       => 'roles.index',
    'title'    => 'roles.index',
    'sub_link' => true,
]);

# add role page
Route::get('roles/create', [
    'uses'  => 'RoleController@create',
    'as'    => 'roles.create',
    'title' => 'roles.create_page',

]);

# store role
Route::post('roles/store', [
    'uses'  => 'RoleController@store',
    'as'    => 'roles.store',
    'title' => 'roles.create',
]);

# edit role page
Route::get(
    'roles/{id}/edit',
    [
        'uses'  => 'RoleController@edit',
        'as'    => 'roles.edit',
        'title' => 'roles.edit_page',
    ]
);

# update role
Route::put('roles/{id}', [
    'uses'  => 'RoleController@update',
    'as'    => 'roles.update',
    'title' => 'roles.edit',
]);

# delete role
Route::delete('roles/{id}', [
    'uses'  => 'RoleController@destroy',
    'as'    => 'roles.delete',
    'title' => 'roles.delete',
]);
/*------------ end Of Roles----------*/

/*------------ start Of reports----------*/
Route::get('reports', [
    'uses'     => 'ReportController@index',
    'as'       => 'reports',
    'sub_link' => true,
    'title'    => 'reports.index',
]);

# reports show
Route::get('reports/{id}', [
    'uses'  => 'ReportController@show',
    'as'    => 'reports.show',
    'title' => 'reports.show',
]);

# reports delete
Route::delete('reports/{id}', [
    'uses'  => 'ReportController@destroy',
    'as'    => 'reports.delete',
    'title' => 'reports.delete',
]);

# delete all reports
Route::post('delete-all-reports', [
    'uses'  => 'ReportController@destroyAll',
    'as'    => 'reports.deleteAll',
    'title' => 'reports.delete_all',
]);
/*------------ end Of reports ----------*/
