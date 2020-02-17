<?php
/**
 * Created by PhpStorm.
 * User: jony
 * Date: 7/24/19
 * Time: 1:37 PM
 */

use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'user','namespace'=>'User','middleware'=> ['auth.user','user.role.check']],function (){

    Route::get('/dashboard', 'DeshboardController@index')->name('UserDashboard');
    Route::get('/buy-coin', 'CoinController@index')->name('buyCoin');
    Route::get('/bank-details', 'CoinController@bankDetails')->name('bankDetails');
    Route::post('/buy-coin', 'CoinController@buyCoin')->name('buyCoin');
    Route::get('/referral-bonus', 'CoinController@referralBonus')->name('referralBonus');
    Route::get('/my-Wallet', 'WalletController@index')->name('myWallet');
    Route::get('/make-account-default-{account_id}', 'WalletController@makeDefaultAccount')->name('makeDefaultAccount');
    Route::get('/generate/new-address', 'WalletController@generateNewAddress')->name('generateNewAddress');
    Route::get('/qrcode/generate', 'WalletController@qrCodeGenerate')->name('qrCodeGenerate');

    Route::any('/Wallet-create', 'WalletController@createWallet')->name('createWallet');
    Route::get('/wallet-details-{id}', 'WalletController@walletDetails')->name('walletDetails');
    Route::post('/Withdraw/balance', 'WalletController@WithdrawBalance')->name('WithdrawBalance');
    Route::get('/my-profile', 'StaticController@myProfile')->name('myProfile');
    Route::post('/user-profile-update', 'StaticController@UserProfileUpdate')->name('UserProfileUpdate');
    Route::get('/referral', 'ReferralController@myReferral')->name('referral');
    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::post('/g2f-secret-save', 'SettingController@g2fSecretSave')->name('g2fSecretSave');
    Route::post('/google/login/enable', 'SettingController@googleLoginEnable')->name('googleLoginEnable');
    Route::get('/send-sms-for-verification', 'SettingController@sendSMS')->name('sendSMS');
    Route::post('/Phone-verify', 'SettingController@PhoneVerify')->name('PhoneVerify');
    Route::post('/nid-upload', 'SettingController@nidUpload')->name('nidUpload');
    Route::post('/pass-upload', 'SettingController@passUpload')->name('passUpload');
    Route::post('/driving-licence-upload', 'SettingController@driveUpload')->name('driveUpload');



    Route::get('/get-btc-rate', 'SettingController@getBtcRate')->name('get-btc-rate');
});

Route::group(['middleware'=> ['auth']], function () {
    Route::post('/upload-profile-image', 'User\StaticController@uploadProfileImage')->name('uploadProfileImage');
    Route::post('/user-profile-update', 'User\StaticController@UserProfileUpdate')->name('UserProfileUpdate');

});