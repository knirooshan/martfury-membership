<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Membership\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        // Plans & Tiers Management
        Route::group(['prefix' => 'plans', 'as' => 'plans.'], function () {
            Route::resource('', 'PlanController')->parameters(['' => 'plan']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PlanController@deletes',
                'permission' => 'plans.destroy',
            ]);
        });

        // Memberships Subscriptions Management
        Route::group(['prefix' => 'memberships', 'as' => 'memberships.'], function () {
            Route::resource('', 'MembershipController')->parameters(['' => 'membership']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'MembershipController@deletes',
                'permission' => 'memberships.destroy',
            ]);
        });
    });
});
