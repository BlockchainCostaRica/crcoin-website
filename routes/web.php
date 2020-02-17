<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware'=> 'installation'],function () {
    include 'users.php';
    include 'admin.php';

    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/404', function () {
        return view('404');
    })->name('404');
    Route::get('page/{id}/{key}', 'HomeController@getCustomPage')->name('getCustomPage');
    Route::get('terms-and-condition', 'HomeController@termsAndCondition')->name('termsAndCondition');

    Route::group([], function () {

        Route::get('login', 'AuthController@login')->name('login');
        Route::get('forgot-password', 'AuthController@forgotPassword')->name('forgotPassword');
        Route::get('signup', 'AuthController@signup')->name('signup');
        Route::post('login-process', 'AuthController@postLogin')->name('postLogin');
        Route::post('registration/process', 'AuthController@registerSave')->name('registerSave');
        Route::get('verify-email', 'AuthController@verifyEmailPost')->name('verifyWeb');
        Route::get('reset-password', 'AuthController@resetPassword')->name('resetPasswordPage');
        Route::post('send-token', 'AuthController@sendToken')->name('sendToken');
        Route::get('send-token', 'AuthController@sendToken')->name('sendToken');
        Route::post('reset-password-save', 'AuthController@resetPasswordSave')->name('resetPasswordSave');
        Route::post('change-password-save', 'AuthController@changePasswordSave')->name('changePasswordSave');
        Route::get('/g2f-checked', 'AuthController@g2fChecked')->name('g2fChecked');
        Route::post('/g2f-verify', 'AuthController@g2fVerify')->name('g2fVerify');
        Route::get('/user/logout', 'AuthController@logout')->name('logoutUser');
        Route::post('/contact-us', 'AuthController@ContactUs')->name('ContactUs');

    });
    Route::get('/transaction-histories', 'TransactionController@transactionHistories')->name('transactionHistories')->middleware('auth.user');

//    Auth::routes();
    // Referral Registration
    Route::get('referral-reg', 'User\ReferralController@signup')->name('referral.registration');


});

