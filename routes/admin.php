<?php
/**
 * Created by PhpStorm.
 * User: jony
 * Date: 7/24/19
 * Time: 1:37 PM
 */

Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>['auth.user','admin.role.check']], function () {
    Route::get('/dashboard', 'DeshboardController@index')->name('AdminDashboard');
    Route::get('/profile', 'DeshboardController@adminProfile')->name('adminProfile');
    Route::get('/users', 'UserController@index')->name('admin.users');
    Route::get('/user-add', 'UserController@UserAddEdit')->name('admin.UserAddEdit');
    Route::get('/user-edit', 'UserController@UserEdit')->name('admin.UserEdit');
    Route::get('/user-delete-{id}', 'UserController@adminUserDelete')->name('admin.user.delete');
    Route::get('/user-suspend-{id}', 'UserController@adminUserSuspend')->name('admin.user.suspend');
    Route::get('/user-active-{id}', 'UserController@adminUserActive')->name('admin.user.active');
    Route::get('/user-remove-gauth-set-{id}', 'UserController@adminUserRemoveGauth')->name('admin.user.remove.gauth');
    Route::get('/user-email-verify-{id}', 'UserController@adminUserEmailVerified')->name('admin.user.email.verify');
    Route::get('deleted-users', 'UserController@adminDeletedUser')->name('adminDeletedUser');
    Route::get('verification-details-{id}', 'UserController@VerificationDetails')->name('adminUserDetails');
    // ID Varification
    Route::get('pending-id-verified-user', 'UserController@adminUserIdVerificationPending')->name('adminUserIdVerificationPending');

    Route::get('varification-active-{id}-{type}', 'UserController@adminUserVerificationActive')->name('adminUserVerificationActive');
    Route::get('varification-reject', 'UserController@varificationReject')->name('varificationReject');
    // transaction route
    // Wallets
    Route::get('wallets', 'WalletController@adminWallets')->name('adminWallets');
    Route::get('wallet-list', 'WalletController@adminWalletList')->name('adminWalletList');
    // Coin routes starts here
    Route::get('coins', 'SettingsController@adminCoins')->name('adminCoins');

    // Transaction
    Route::get('transaction-history', 'TransactionController@adminDepositHistory')->name('adminDepositHistory');
    Route::get('withdrawal-history', 'TransactionController@adminWithdrawalHistory')->name('adminWithdrawalHistory');

    Route::get('buy-coin-order', 'TransactionController@adminPendingDeposit')->name('adminPendingDeposit');
    Route::get('rejected-deposite', 'TransactionController@adminRejectedDeposit')->name('adminRejectedDeposit');
    Route::get('active-deposite', 'TransactionController@adminActiveDeposit')->name('adminActiveDeposit');

    Route::get('pending-withdrawal', 'TransactionController@adminPendingWithdrawal')->name('adminPendingWithdrawal');
    Route::get('rejected-withdrawal', 'TransactionController@adminRejectedWithdrawal')->name('adminRejectedWithdrawal');
    Route::get('active-withdrawal', 'TransactionController@adminActiveWithdrawal')->name('adminActiveWithdrawal');


    Route::get('transaction-hash-details', 'TransactionController@adminTransactionHashDetails')->name('adminTransactionHashDetails');
    Route::post('transaction-hash-details', 'TransactionController@adminTransactionHashDetails')->name('adminTransactionHashDetails');

    Route::get('accept-pending-withdrawal-{id}', 'TransactionController@adminAcceptPendingWithdrawal')->name('adminAcceptPendingWithdrawal');
    Route::get('reject-pending-withdrawal-{id}', 'TransactionController@adminRejectPendingWithdrawal')->name('adminRejectPendingWithdrawal');

    Route::get('accept-pending-buy-coin-{id}', 'TransactionController@adminAcceptPendingBuyCoin')->name('adminAcceptPendingBuyCoin');
    Route::get('reject-pending-buy-coin-{id}', 'TransactionController@adminRejectPendingBuyCoin')->name('adminRejectPendingBuyCoin');
    // buy coin part
    Route::get('coin-order-list', 'TransactionController@coinOrderList')->name('coinOrderList');

    //   settings
    // Settings
    Route::get('settings', 'SettingsController@adminSettings')->name('adminSettings');
    Route::post('common-settings', 'SettingsController@adminCommonSettings')->name('adminCommonSettings');
    Route::post('coin-api-settings', 'SettingsController@adminCoinApiSettings')->name('adminCoinApiSettings');
    Route::post('sms-save-settings', 'SettingsController@adminSaveSmsSettings')->name('adminSaveSmsSettings');
    Route::post('braintree-settings', 'SettingsController@adminBraintreeApiSettings')->name('adminBraintreeApiSettings');
    Route::post('send-fees-settings', 'SettingsController@adminSendFeesSettings')->name('adminSendFeesSettings');
    Route::post('referral-fees-settings', 'SettingsController@adminReferralFeesSettings')->name('adminReferralFeesSettings');
    Route::post('withdrawal-settings', 'SettingsController@adminWithdrawalSettings')->name('adminWithdrawalSettings');
    Route::post('order-settings', 'SettingsController@adminOrderSettings')->name('adminOrderSettings');


    //CMS
    Route::get('custom-page-list', 'SettingsController@adminCustomPageList')->name('adminCustomPageList');
    Route::get('custom-page-add', 'SettingsController@adminCustomPageSettingAdd')->name('adminCustomPageSettingAdd');
    Route::get('custom-page-edit-{id}', 'SettingsController@adminCustomPageSettingEdit')->name('adminCustomPageSettingEdit');
    Route::get('custom-page-delete-{id}', 'SettingsController@adminCustomPageSettingDelete')->name('adminCustomPageSettingDelete');
    Route::get('custom-page-order', 'SettingsController@customPageOrder')->name('customPageOrder');
    Route::post('custom-page-setting-save', 'SettingsController@adminCustomPageSettingSave')->name('adminCustomPageSettingSave');
    Route::get('landing-page-setting', 'CmsController@adminCmsSetting')->name('adminCmsSetting');
    Route::post('landing-page-setting-save', 'CmsController@adminCmsSettingSave')->name('adminCmsSettingSave');
    Route::get('landing-page-content', 'CmsController@adminCmsLandingPpageContent')->name('adminCmsLandingPpageContent');
    Route::get('landing-page-content-add', 'CmsController@adminCmsLandingPpageContentAdd')->name('adminCmsLandingPpageContentAdd');
    Route::post('landing-page-content-save', 'CmsController@adminCmsLandingPpageContentSave')->name('adminCmsLandingPpageSaveContent');
    Route::get('landing-page-content-edit-{id}', 'CmsController@adminCmsLandingPpageContentEdit')->name('adminCmsLandingPpageContentEdit');
    Route::get('landing-page-content-delete-{id}', 'CmsController@adminCmsLandingPpageContentDelete')->name('adminCmsLandingPpageContentDelete');
    Route::get('landing-page-save', 'CmsController@adminCmsLandingPpageContentSave')->name('adminCmsLandingPpageContentSave');

    //FAQ
    Route::get('faq-list', 'SettingsController@adminFaqList')->name('adminFaqList');
    Route::get('faq-add', 'SettingsController@adminFaqAdd')->name('adminFaqAdd');
    Route::post('faq-save', 'SettingsController@adminFaqSave')->name('adminFaqSave');
    Route::get('faq-edit-{id}', 'SettingsController@adminFaqEdit')->name('adminFaqEdit');
    Route::get('faq-delete-{id}', 'SettingsController@adminFaqDelete')->name('adminFaqDelete');
    //Mail queue
    Route::get('send-notification', 'NotificationController@adminSendNotification')->name('adminSendNotification');
    Route::get('clear-email-record', 'NotificationController@clearEmailRecord')->name('clearEmailRecord');
    Route::post('adminSendMailProcess', 'NotificationController@adminSendMailProcess')->name('adminSendMailProcess');

    // bank
    Route::get('banks', 'BankController@bankList')->name('bankList');
    Route::get('bank-add', 'BankController@bankAdd')->name('bankAdd');
    Route::get('bank-edit-{id}', 'BankController@bankEdit')->name('bankEdit');
    Route::get('bank-delete-{id}', 'BankController@bankDelete')->name('bankDelete');
    Route::post('bank-add-process', 'BankController@bankAddProcess')->name('bankAddProcess');

    // Aweber
    Route::get('aweber', 'SettingController@aweberAuthorize');
    Route::get('aweber_callback', 'SettingController@aweberAuthorizeCallback');
});
