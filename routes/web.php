<?php

Route::group(['namespace' => 'Botble\Membership\Http\Controllers', 'middleware' => ['web', 'core']], function(){

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function(){

        Route::group(['prefix' => 'membership', 'as' => 'membership.'], function(){

            Route::get('members', 'MembershipController@getMembers')->name('members');
            Route::get('members/{id}', 'MembershipController@getMember')->name('members.edit');
            Route::post('members/{id}', 'MembershipController@postMember')->name('members.edit');
            Route::get('members/{id}/delete', 'MembershipController@deleteMember')->name('members.delete');

            Route::get('plans', 'MembershipController@getPlans')->name('plans');
            Route::get('plans/{id}', 'MembershipController@getPlan')->name('plans.edit');
            Route::post('plans/{id}', 'MembershipController@postPlan')->name('plans.edit');
            Route::get('plans/{id}/delete', 'MembershipController@deletePlan')->name('plans.delete');

        });

    });

});