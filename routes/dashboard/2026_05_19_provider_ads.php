<?php

use Illuminate\Support\Facades\Route;

Route::get('provider-ads', [
    'uses'          => 'ProviderAdController@index',
    'as'            => 'provider-ads.index',
    'title'         => 'provider_ads',
    'sub_link'      => true,
    'icon'          => '<i class="feather icon-radio"></i>',
    'type'          => 'parent',
    'has_sub_route' => true,
    'child'         => [
        'provider-ads.index',
        'provider-ads.show',
        'provider-ads.approve',
        'provider-ads.reject',
    ],
]);

Route::get('provider-ads/{id}/show', [
    'uses'  => 'ProviderAdController@show',
    'as'    => 'provider-ads.show',
    'title' => 'provider_ads',
]);

Route::post('provider-ads/{id}/approve', [
    'uses'  => 'ProviderAdController@approve',
    'as'    => 'provider-ads.approve',
    'title' => 'provider_ads',
]);

Route::post('provider-ads/{id}/reject', [
    'uses'  => 'ProviderAdController@reject',
    'as'    => 'provider-ads.reject',
    'title' => 'provider_ads',
]);
