<?php

use Illuminate\Support\Facades\Route;

Route::get('public-sections', [
    'as' => 'public-sections',
    'icon' => '<i class="feather icon-layers"></i>',
    'title' => 'public_sections',
    // 'type' => 'parent',
    'has_sub_route' => true,
    'child' => [

        // 'socials.index',
        // 'socials.create',
        // 'socials.store',
        // 'socials.edit',
        // 'socials.update',
        // 'socials.show',
        // 'socials.delete',
        // 'socials.deleteAll',

        'pages.index',
        'services.index',
        'customers.index',
        'blogs.index',
        'financials.index',
        'galleries.index',
        'jobs.index',
        'sliders.index',
        'colors.index',
        'gas-types.index',
        'settlements.index',
        'order-complaints.index',

    ],
]);



/*------------ start Of contacts ----------*/
Route::get('all-contacts', [
    'as' => 'all_contacts',
    'uses' => 'ContactController@index',
    'sub_link' => true,
    'title' => 'contacts.index',
]);

# contact reply
Route::post('contacts-reply/{id}', [
    'uses' => 'ContactController@reply',
    'as' => 'contacts.reply',
    'title' => 'contacts.the_reply_of_contact',
]);

# socials update
Route::get('contacts/{id}', [
    'uses' => 'ContactController@show',
    'as' => 'contacts.show',
    'title' => 'contacts.the_resolution_of_contact',
]);

# contacts delete
Route::delete('contacts/{id}', [
    'uses' => 'ContactController@destroy',
    'as' => 'contacts.delete',
    'title' => 'contacts.delete',
]);

# delete all contacts
Route::post('delete-all-contacts', [
    'uses' => 'ContactController@destroyAll',
    'as' => 'contacts.deleteAll',
    'title' => 'contacts.delete_all',
]);
/*------------ end Of contacts ----------*/
