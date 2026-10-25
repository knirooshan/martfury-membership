<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Membership\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    // Customer Portal: My Membership
    Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['customer']], function () {
        Route::get('membership', [
            'as'   => 'membership',
            'uses' => 'FrontMembershipController@customerMembership',
        ]);
    });

    // Public Membership Showcase
    Route::get('membership', [
        'as'   => 'public.membership',
        'uses' => 'FrontMembershipController@publicMembership',
    ]);
});
