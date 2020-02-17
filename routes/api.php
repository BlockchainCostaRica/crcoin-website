<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'Api', 'middleware' => ['wallet.notity']], function (){
    Route::post('wallet-notifier','WalletNotifier@walletNofity');
    Route::post('wallet-notifier-confirm','WalletNotifier@notifyConfirm');
});

Route::post('sign-up','Api\AuthController@signUp');
Route::post('login','Api\AuthController@login');
Route::post('email-verify','Api\AuthController@emailVerify');
Route::post('send-reset-password-code','Api\AuthController@sendResetCode');
Route::post('reset-password','Api\AuthController@resetPassword');

Route::group(['middleware' =>['auth:api','api.user'],'namespace'=>'Api'],function () {
    // home
    Route::get('home', 'HomeController@home');

    //Profile
    Route::get('profile', 'ProfileController@profile');
    Route::post('update-profile', 'ProfileController@profileUpdate');
    Route::post('change-password', 'ProfileController@changePassword');
    // set device id
    Route::get('set-user-device-id/{device_id}', 'AuthController@setDeviceId');
    // wallet
    Route::get('my-wallet-list', 'WalletController@myWalletList');
    Route::post('create-wallet', 'WalletController@createWallet');
    Route::post('generate-wallet-address', 'WalletController@generateWalletAddress');
    Route::get('wallet-transaction-history-{id}', 'WalletController@walletTransactionHistory');
    Route::get('all-activity', 'WalletController@allActivity');
    // referral
    Route::get('my-referral', 'ProfileController@myReferral');
    Route::get('bank-list', 'UserController@bankList');
    Route::get('bank-details-{id}', 'UserController@bankDetails');

    Route::post('buy-coin', 'CoinController@buyCoinProcess');
    Route::post('withdrawal-balance', 'WalletController@withdrawalProcess');
});

