<?php

use Illuminate\Support\Facades\Route;

Route::get('intro-site', [
    'icon'          => '<i class="feather icon-map"></i>',
    'title'         => 'introductory_site',
    'type'          => 'parent',
    'has_sub_route' => true,
    'child'         => [
        'intro_settings.index',
        'intro_settings.update',


        'intros.index',
        'intros.show',
        'intros.create',
        'intros.store',
        'intros.edit',
        'intros.update',
        'intros.delete',
        'intros.deleteAll',

        
        'introsliders.show',
        'introsliders.index',
        'introsliders.store',
        'introsliders.update',
        'introsliders.delete',
        'introsliders.deleteAll',
        'introsliders.create',
        'introsliders.edit',

        'introservices.show',
        'introservices.index',
        'introservices.create',
        'introservices.store',
        'introservices.edit',
        'introservices.update',
        'introservices.delete',
        'introservices.deleteAll',

        'introfqscategories.show',
        'introfqscategories.index',
        'introfqscategories.store',
        'introfqscategories.create',
        'introfqscategories.edit',
        'introfqscategories.update',
        'introfqscategories.delete',
        'introfqscategories.deleteAll',

        'introfqs.show',
        'introfqs.index',
        'introfqs.store',
        'introfqs.update',
        'introfqs.delete',
        'introfqs.deleteAll',
        'introfqs.edit',
        'introfqs.create',

        'intropartners.create',
        'intropartners.show',
        'intropartners.index',
        'intropartners.store',
        'intropartners.edit',
        'intropartners.update',
        'intropartners.delete',
        'intropartners.deleteAll',

        'intromessages.index',
        'intromessages.delete',
        'intromessages.deleteAll',
        'intromessages.show',

        'introsocials.show',
        'introsocials.index',
        'introsocials.store',
        'introsocials.update',
        'introsocials.delete',
        'introsocials.deleteAll',
        'introsocials.edit',
        'introsocials.create',

        'introhowworks.show',
        'introhowworks.index',
        'introhowworks.store',
        'introhowworks.update',
        'introhowworks.delete',
        'introhowworks.deleteAll',
        'introhowworks.create',
        'introhowworks.edit',
    ],
]);

Route::get('intro-settings', [
    'uses'     => 'IntroSetting@index',
    'as'       => 'intro_settings.index',
    'title'    => 'introductory_site_setting',
    'sub_link' => true,
]);

Route::put('intro-settings', [
    'uses'     => 'IntroSetting@update',
    'as'       => 'intro_settings.update',
    'title'    => 'update_intro_settings',
]);

/*------------ start Of introsliders ----------*/
Route::get('introsliders', [
    'uses'     => 'IntroSliderController@index',
    'as'       => 'introsliders.index',
    'title'    => 'intro_slider.index',
    'sub_link' => true,
]);

# socials store
Route::get('introsliders/create', [
    'uses'  => 'IntroSliderController@create',
    'as'    => 'introsliders.create',
    'title' => 'intro_slider.create_page',
]);

# introsliders store
Route::post('introsliders/store', [
    'uses'  => 'IntroSliderController@store',
    'as'    => 'introsliders.store',
    'title' => 'intro_slider.create',
]);

# socials update
Route::get('introsliders/{id}/edit', [
    'uses'  => 'IntroSliderController@edit',
    'as'    => 'introsliders.edit',
    'title' => 'intro_slider.edit_page',
]);

# introsliders update
Route::put('introsliders/{id}', [
    'uses'  => 'IntroSliderController@update',
    'as'    => 'introsliders.update',
    'title' => 'intro_slider.edit',
]);

# introsliders update
Route::get('introsliders/{id}/Show', [
    'uses'  => 'IntroSliderController@show',
    'as'    => 'introsliders.show',
    'title' => 'intro_slider.show',
]);

# introsliders delete
Route::delete('introsliders/{id}', [
    'uses'  => 'IntroSliderController@destroy',
    'as'    => 'introsliders.delete',
    'title' => 'intro_slider.delete',
]);

# delete all introsliders
Route::post('delete-all-introsliders', [
    'uses'  => 'IntroSliderController@destroyAll',
    'as'    => 'introsliders.deleteAll',
    'title' => 'intro_slider.delete_all',
]);
/*------------ end Of introsliders ----------*/

/*------------ start Of introservices ----------*/
Route::get('introservices', [
    'uses'     => 'IntroServiceController@index',
    'as'       => 'introservices.index',
    'title'    => 'our_services.index',
    'sub_link' => true,
]);

# introservices update
Route::get('introservices/{id}/Show', [
    'uses'  => 'IntroServiceController@show',
    'as'    => 'introservices.show',
    'title' => 'our_services.show',
]);

# socials store
Route::get('introservices/create', [
    'uses'  => 'IntroServiceController@create',
    'as'    => 'introservices.create',
    'title' => 'our_services.create_page',
]);

# introservices store
Route::post('introservices/store', [
    'uses'  => 'IntroServiceController@store',
    'as'    => 'introservices.store',
    'title' => 'our_services.create',
]);

# socials update
Route::get('introservices/{id}/edit', [
    'uses'  => 'IntroServiceController@edit',
    'as'    => 'introservices.edit',
    'title' => 'our_services.edit_page',
]);

# introservices update
Route::put('introservices/{id}', [
    'uses'  => 'IntroServiceController@update',
    'as'    => 'introservices.update',
    'title' => 'our_services.edit',
]);

# introservices delete
Route::delete('introservices/{id}', [
    'uses'  => 'IntroServiceController@destroy',
    'as'    => 'introservices.delete',
    'title' => 'our_services.delete',
]);

# delete all introservices
Route::post('delete-all-introservices', [
    'uses'  => 'IntroServiceController@destroyAll',
    'as'    => 'introservices.deleteAll',
    'title' => 'our_services.delete_all',
]);
/*------------ end Of introservices ----------*/

/*------------ start Of introfqscategories ----------*/
Route::get('introfqscategories', [
    'uses'     => 'IntroFqsCategoryController@index',
    'as'       => 'introfqscategories.index',
    'title'    => 'common_questions_sections.index',
    'sub_link' => true,
]);

# socials store
Route::get('introfqscategories/create', [
    'uses'  => 'IntroFqsCategoryController@create',
    'as'    => 'introfqscategories.create',
    'title' => 'common_questions_sections.create_page',
]);

# introfqscategories store
Route::post('introfqscategories/store', [
    'uses'  => 'IntroFqsCategoryController@store',
    'as'    => 'introfqscategories.store',
    'title' => 'common_questions_sections.create',
]);

# introfqscategories update
Route::get('introfqscategories/{id}/edit', [
    'uses'  => 'IntroFqsCategoryController@edit',
    'as'    => 'introfqscategories.edit',
    'title' => 'common_questions_sections.edit_page',
]);

# introfqscategories update
Route::put('introfqscategories/{id}', [
    'uses'  => 'IntroFqsCategoryController@update',
    'as'    => 'introfqscategories.update',
    'title' => 'common_questions_sections.edit',
]);

# introfqscategories update
Route::get('introfqscategories/{id}/Show', [
    'uses'  => 'IntroFqsCategoryController@show',
    'as'    => 'introfqscategories.show',
    'title' => 'common_questions_sections.show',
]);

# introfqscategories delete
Route::delete('introfqscategories/{id}', [
    'uses'  => 'IntroFqsCategoryController@destroy',
    'as'    => 'introfqscategories.delete',
    'title' => 'common_questions_sections.delete',
]);

# delete all introfqscategories
Route::post('delete-all-introfqscategories', [
    'uses'  => 'IntroFqsCategoryController@destroyAll',
    'as'    => 'introfqscategories.deleteAll',
    'title' => 'common_questions_sections.delete_all',
]);
/*------------ end Of introfqscategories ----------*/

/*------------ start Of introfqs ----------*/
Route::get('introfqs', [
    'uses'     => 'IntroFqsController@index',
    'as'       => 'introfqs.index',
    'title'    => 'questions_sections.index',
    'sub_link' => true,
]);

# socials store
Route::get('introfqs/create', [
    'uses'  => 'IntroFqsController@create',
    'as'    => 'introfqs.create',
    'title' => 'questions_sections.create_page',
]);

# introfqs store
Route::post('introfqs/store', [
    'uses'  => 'IntroFqsController@store',
    'as'    => 'introfqs.store',
    'title' => 'questions_sections.create',
]);

# introfqscategories update
Route::get('introfqs/{id}/edit', [
    'uses'  => 'IntroFqsController@edit',
    'as'    => 'introfqs.edit',
    'title' => 'questions_sections.edit_page',
]);

# introfqscategories update
Route::get('introfqs/{id}/Show', [
    'uses'  => 'IntroFqsController@show',
    'as'    => 'introfqs.show',
    'title' => 'questions_sections.show',
]);

# introfqs update
Route::put('introfqs/{id}', [
    'uses'  => 'IntroFqsController@update',
    'as'    => 'introfqs.update',
    'title' => 'questions_sections.edit',
]);

# introfqs delete
Route::delete('introfqs/{id}', [
    'uses'  => 'IntroFqsController@destroy',
    'as'    => 'introfqs.delete',
    'title' => 'questions_sections.delete',
]);

# delete all introfqs
Route::post('delete-all-introfqs', [
    'uses'  => 'IntroFqsController@destroyAll',
    'as'    => 'introfqs.deleteAll',
    'title' => 'questions_sections.delete_all',
]);
/*------------ end Of introfqs ----------*/

/*------------ start Of intropartners ----------*/
Route::get('introPartners', [
    'uses'     => 'IntroPartnerController@index',
    'as'       => 'intropartners.index',
    'title'    => 'success_partners.index',
    'sub_link' => true,
]);

# intropartners update
Route::get('introPartners/{id}/Show', [
    'uses'  => 'IntroPartnerController@show',
    'as'    => 'intropartners.show',
    'title' => 'success_partners.show',
]);

# socials store
Route::get('introPartners/create', [
    'uses'  => 'IntroPartnerController@create',
    'as'    => 'intropartners.create',
    'title' => 'success_partners.create_page',
]);

# intropartners store
Route::post('introPartners/store', [
    'uses'  => 'IntroPartnerController@store',
    'as'    => 'intropartners.store',
    'title' => 'success_partners.create',
]);

# intropartners update
Route::get('introPartners/{id}/edit', [
    'uses'  => 'IntroPartnerController@edit',
    'as'    => 'intropartners.edit',
    'title' => 'success_partners.edit_page',
]);

# intropartners update
Route::put('introPartners/{id}', [
    'uses'  => 'IntroPartnerController@update',
    'as'    => 'intropartners.update',
    'title' => 'success_partners.edit',
]);

# intropartners delete
Route::delete('introPartners/{id}', [
    'uses'  => 'IntroPartnerController@destroy',
    'as'    => 'intropartners.delete',
    'title' => 'success_partners.delete',
]);

# delete all intropartners
Route::post('delete-all-intropartners', [
    'uses'  => 'IntroPartnerController@destroyAll',
    'as'    => 'intropartners.deleteAll',
    'title' => 'success_partners.delete_all',
]);
/*------------ end Of intropartners ----------*/

/*------------ start Of intromessages ----------*/
Route::get('intromessages', [
    'uses'     => 'IntroMessagesController@index',
    'as'       => 'intromessages.index',
    'title'    => 'customer_messages.index',
    'sub_link' => true,
]);

# socials update
Route::get('intromessages/{id}', [
    'uses'  => 'IntroMessagesController@show',
    'as'    => 'intromessages.show',
    'title' => 'customer_messages.show',
]);

# intromessages delete
Route::delete('intromessages/{id}', [
    'uses'  => 'IntroMessagesController@destroy',
    'as'    => 'intromessages.delete',
    'title' => 'customer_messages.delete',
]);

# delete all intromessages
Route::post('delete-all-intromessages', [
    'uses'  => 'IntroMessagesController@destroyAll',
    'as'    => 'intromessages.deleteAll',
    'title' => 'customer_messages.delete_all',
]);
/*------------ end Of intromessages ----------*/

/*------------ start Of introsocials ----------*/
Route::get('introsocials', [
    'uses'     => 'IntroSocialController@index',
    'as'       => 'introsocials.index',
    'title'    => 'socials.index',
    'sub_link' => true,
]);

# introsocials update
Route::get('introsocials/{id}/Show', [
    'uses'  => 'IntroSocialController@show',
    'as'    => 'introsocials.show',
    'title' => 'socials.show',
]);

# introsocials store
Route::get('introsocials/create', [
    'uses'  => 'IntroSocialController@create',
    'as'    => 'introsocials.create',
    'title' => 'socials.create_page',
]);

# introsocials store
Route::post('introsocials/store', [
    'uses'  => 'IntroSocialController@store',
    'as'    => 'introsocials.store',
    'title' => 'socials.create',
]);

# introsocials update
Route::get('introsocials/{id}/edit', [
    'uses'  => 'IntroSocialController@edit',
    'as'    => 'introsocials.edit',
    'title' => 'socials.edit_page',
]);

# introsocials update
Route::put('introsocials/{id}', [
    'uses'  => 'IntroSocialController@update',
    'as'    => 'introsocials.update',
    'title' => 'socials.edit',
]);

# introsocials delete
Route::delete('introsocials/{id}', [
    'uses'  => 'IntroSocialController@destroy',
    'as'    => 'introsocials.delete',
    'title' => 'socials.delete',
]);

# delete all introsocials
Route::post('delete-all-introsocials', [
    'uses'  => 'IntroSocialController@destroyAll',
    'as'    => 'introsocials.deleteAll',
    'title' => 'socials.delete_all',
]);
/*------------ end Of introsocials ----------*/

/*------------ start Of introhowworks ----------*/
Route::get('introhowworks', [
    'uses'     => 'IntroHowWorkController@index',
    'as'       => 'introhowworks.index',
    'title'    => 'how_the_site_works.index',
    'sub_link' => true,
]);

# introhowworks store
Route::get('introhowworks/create', [
    'uses'  => 'IntroHowWorkController@create',
    'as'    => 'introhowworks.create',
    'title' => 'how_the_site_works.create_page',
]);

# introfqscategories update
Route::get('introhowworks/{id}/Show', [
    'uses'  => 'IntroHowWorkController@show',
    'as'    => 'introhowworks.show',
    'title' => 'how_the_site_works.show',
]);

# introhowworks update
Route::get('introhowworks/{id}/edit', [
    'uses'  => 'IntroHowWorkController@edit',
    'as'    => 'introhowworks.edit',
    'title' => 'how_the_site_works.edit_page',
]);

# introhowworks store
Route::post('introhowworks/store', [
    'uses'  => 'IntroHowWorkController@store',
    'as'    => 'introhowworks.store',
    'title' => 'how_the_site_works.create',
]);

# introhowworks update
Route::put('introhowworks/{id}', [
    'uses'  => 'IntroHowWorkController@update',
    'as'    => 'introhowworks.update',
    'title' => 'how_the_site_works.edit',
]);

# introhowworks delete
Route::delete('introhowworks/{id}', [
    'uses'  => 'IntroHowWorkController@destroy',
    'as'    => 'introhowworks.delete',
    'title' => 'how_the_site_works.delete',
]);

# delete all introhowworks
Route::post('delete-all-introhowworks', [
    'uses'  => 'IntroHowWorkController@destroyAll',
    'as'    => 'introhowworks.deleteAll',
    'title' => 'how_the_site_works.delete_all',
]);
/*------------ end Of introhowworks ----------*/
