<?php

namespace App\Http\Services;

use App\Model\User\AffiliationCode;
use App\Model\User\DepositeTransaction;
use App\Model\User\ReferralUser;
use App\Model\User\UserSetting;
use App\Model\User\UserVerificationCode;
use App\Model\User\Wallet;
use App\Model\User\WalletAddressHistory;
use App\Model\User\WithdrawHistory;
use App\Repository\AffiliateRepository;
use App\Repository\UserRepository;
use App\Services\BitCoinApiService;
use App\Services\MailService;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionService
{
    protected $logger;

    public function __construct()
    {

    }

    private function generate_email_verification_key()
    {
        do {
            $key = Str::random(60);
        } While (User::where('email_verified', $key)->count() > 0);

        return $key;
    }

    // User sms verification
    public function sendSmsVerificationCode()
    {
        $randno = randomNumber(6);
        $smsText = 'Your ' . allsetting()['app_title'] . ' verification code is here ' . $randno;
        app(SmsService::class)->send(Auth::user()->phone, $smsText);
        $update = User::where(['id' => Auth::user()->id])->update(['phone_verification' => $randno]);
        if ( !$update ) {
            return [
                'success' => false,
                'message' => __('Failed to send Sms!'),
            ];
        }

        return [
            'success' => true,
            'message' => __('Sms sent successfully!'),
        ];
    }

    //  create new Wallet
    public function createWallet($request)
    {
        $request->validate(['name' => 'required']);
        $coin = Coin::where(['is_default' => COIN_DEFAULT])->first();
        if ( isset($coin) ) {
            $insert_wallet = ['user_id' => Auth::user()->id, 'name' => $request->name, 'coin_id' => $coin->id, 'is_primary' => 0, 'balance' => 0];
            $wallet_info = MtrWallet::create($insert_wallet);
            if ( isset($wallet_info) ) {
                $wallet_address = WalletAddress::create(['mtr_wallet_id' => $wallet_info->id, 'address' => $this->generate_address()]);
            }
            if ( !$wallet_address ) {
                return ['success' => false, 'message' => __('Failed to create wallet!')];
            }
            return ['success' => true, 'data' => ['wallet_info' => $wallet_info, 'wallet_address' => $wallet_address], 'message' => __('Wallet created successfully!')];
        } else {
            return ['success' => false, 'message' => __('C-Type not found!')];
        }
    }

    // Wallet new address
    public function generate_address()
    {
        //        ['tcn_host', 'tcn_port', 'tcn_user', 'tcn_pass']
        $tcnCredentials = allsetting();
        $api = new BitCoinApiService($tcnCredentials['coin_api_user'], $tcnCredentials['coin_api_pass'], $tcnCredentials['coin_api_host'], $tcnCredentials['coin_api_port']);
        $address = $api->getNewAddress();

        return $address;
    }

    // get Address in Order process
    public function generate_address_by_coin($user, $pass, $host, $port)
    {
        $api = new BitCoinApiService($user, $pass, $host, $port);
        $address = $api->getNewAddress();

        return $address;
    }

    /*Phone verification check*/
    public function phonesecurity($request)
    {
        $sms_ver_code = UserVerificationCode::where(['code' => $request->phone_verification, 'status' => STATUS_PENDING, 'type' => 2])
            ->where('expired_at', '>=', date('Y-m-d'))->first();
        if ( isset($sms_ver_code) ) {
            UserVerificationCode::where('id', $sms_ver_code->id)->update(['status' => STATUS_SUCCESS]);
            User::where(['id' => $sms_ver_code->user_id])->update(['phone_verified' => STATUS_SUCCESS]);
        } else {
            return [
                'success' => false,
                'message' => __("Code doesn't match!"),
            ];
        }

        return [
            'success' => true,
            'message' => __("Phone verified successfully."),
        ];
    }

    /*Email Verification check*/
    public function emailcurity($request)
    {
        $sms_ver_code = UserVerificationCode::where(['code' => $request->email_verification, 'status' => STATUS_PENDING, 'type' => 1])
            ->where('expired_at', '>=', date('Y-m-d'))->first();
        if ( isset($sms_ver_code) ) {
            UserVerificationCode::where('id', $sms_ver_code->id)->update(['status' => STATUS_SUCCESS]);
            User::where(['id' => $sms_ver_code->user_id])->update(['email_verified' => STATUS_SUCCESS]);
        } else {
            return ['success' => false, 'message' => __("Code doesn't match!"),];
        }
        return ['success' => true, 'message' => __("Email verified successfully.")];
    }

    public function save_setting($request)
    {

        if ( !is_null($request->phone) && !is_null($request->phn_tab) ) {
            $rules['phone'] = 'numeric|required|regex:/[0-9]{10}/|phone_number|unique:users,phone,' . Auth::user()->id;
        } else {
            $data = ['success' => false, 'data' => ['is_phone_updated' => false], 'message' => __('Something Went Wrong.')];
            $rules = ['first_name' => 'required|max:256', 'last_name' => 'required|max:256'];
        }

        $validator = Validator::make($request->all(), $rules);
        if ( $validator->fails() ) {
            $errors = [];
            $e = $validator->errors()->all();
            foreach ($e as $error) {
                $errors[] = $error;
            }
            $data['success'] = false;
            $data['message'] = $errors;
            //            dd($data);
            return $data;
        }
        $user = User::where(['id' => Auth::user()->id]);

        if ( empty($request->phone) ) {
            $firstName = $request->first_name;
            $lastName = $request->last_name;
            $email = $request->email;
            $update ['first_name'] = $firstName;
            $update ['last_name'] = $lastName;
            if ( !empty($request->email) && Auth::user()->email != $request->email ) {
                if ( User::where('email', $request->email)->count() > 0 ) {
                    $data['success'] = false;
                    $data['message'] = __('Email already exists!');
                    return $data;
                } else {
                    $mail_key = $this->generate_email_verification_key();
                    $user = Auth::user();
                    $emailData['user'] = Auth::user();
                    $emailData['key'] = $mail_key;
                    UserVerificationCode::create(
                        ['user_id' => Auth::user()->id
                            , 'type' => 1
                            , 'code' => $mail_key
                            , 'expired_at' => date('Y-m-d', strtotime('+15 days'))
                            , 'status' => STATUS_PENDING]
                    );

                    UserRepository::createUserActivity(Auth::user()->id, USER_ACTIVITY_UPDATE_EMAIL);
                    $activity = UserActivity::where(['user_id' => $user->id, 'action' => USER_ACTIVITY_UPDATE_EMAIL])
                        ->orderBy('created_at', 'desc')->get()->first();
                    try {
                        DB::beginTransaction();
                        $oldEmail = new OldEmail;
                        $oldEmail->user_id = $user->id;
                        $oldEmail->old_email = $user->email;
                        $oldEmail->new_email = $request->email;
                        $oldEmail->user_activity_id = $activity->id;
                        $oldEmail->save();

                        $update['email'] = $request->email;
                        $update['email_verified'] = 0;
                        $user->update($update);
                        DB::commit();

                        Mail::send('email.mailVerify', $emailData, function ($message) use ($request) {
                            $message->to($request->email, $request->first_name . ' ' . $request->last_name)->from('noreply@mtcore.com', __('MTCore'))->subject('Email confirmation');
                        });

                        $data = [
                            'success' => true,
                            'message' => __('We have just sent a verification link on Email.'),
                            'update_type' => 'email'
                        ];
                        return $data;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $activity->delete();
                        $data = [
                            'success' => false,
                            'message' => __('We could not update your email address. Please check your email and try again.'),
                        ];
                        return $data;
                    }
                }
            }

            $data['update_type'] = 'profile';
        } else {
            $code = $request->code;
            $phone = $request->phone;
            $phone_marge = $request->code . $request->phone;
            $update ['country_code'] = $code;
            $update ['phone'] = $phone;
            if ( $user->first()->phone != $phone ) {
                if ( !is_null($phone) ) {
                    $randno = randomNumber(6);
                    $smsText = 'Your ' . allsetting()['app_title'] . ' verification code is here ' . $randno;
                    $sendSms = app(SmsService::class)->send($phone_marge, $smsText);
                    if ( !$sendSms ) {
                        $data['success'] = false;
                        $data['data']['is_phone_updated'] = false;
                        $data['message'] = __('Failed to send verification code');
                    }
                    if ( !is_null($request->phone) ) {
                        User::where(['id' => Auth::user()->id])->update(['phone_verified' => STATUS_PENDING]);
                        UserVerificationCode::create(['user_id' => Auth::user()->id, 'type' => 2, 'code' => $randno, 'expired_at' => date('Y-m-d', strtotime('+15 days')), 'status' => STATUS_PENDING]);

                        $data['data']['is_phone_updated'] = true;
                    }
                }
                $update ['phone'] = $phone;
            }
            $data['update_type'] = 'phone';
        }

        if ( $user->update($update) ) {
            $data['success'] = true;
            $data['message'] = __('Information Updated Successfully');
        }

        return $data;
    }

    public function save_login_setting($request)
    {
        $rules = [
            'password' => 'required|min:8|strong_pass|confirmed',
        ];

        $messages = [
            'password.required' => __('Password field can not be empty'),
            'password.min' => __('Password length must be above 8 characters.'),
            'password.strong_pass' => __('Password must be consist of one Uppercase, one Lowercase and one Number!')
        ];

        $request->validate($rules, $messages);
        $password = $request->password;

        $update ['password'] = Hash::make($password);

        $user = User::where(['id' => Auth::user()->id]);

        if ( $user->update($update) ) {
            return [
                'success' => true,
                'message' => 'Information Updated Successfully'
            ];
        }

        return [
            'success' => false,
            'message' => 'Information Update Failed. Try Again!'
        ];
    }

    public function new_address($request)
    {
        $response = array();
        $wallet_id = $request->get('wallet_id');
        if ( MtrWallet::where('id', $wallet_id)->count() > 0 ) {
            $address = WalletAddress::create(array('mtr_wallet_id' => $wallet_id, 'address' => $this->generate_address()));
            $response['success'] = true;
            $response['address'] = $address->address;
            $response['qr'] = $this->getQRCodeGoogleUrl($address->address, $title = null, $params = array());
        } else {
            $response['success'] = false;
            $response['message'] = "Request not allowed.";
        }

        return $response;
    }

    public function getQRCodeGoogleUrl($address, $title = null, $params = array())
    {
        $width = !empty($params['width']) && (int)$params['width'] > 0 ? (int)$params['width'] : 210;
        $height = !empty($params['height']) && (int)$params['height'] > 0 ? (int)$params['height'] : 204;
        $level = !empty($params['level']) && array_search($params['level'], array('L', 'M', 'Q', 'H')) !== false ? $params['level'] : 'M';

        $urlencoded = $address;
        return 'https://chart.googleapis.com/chart?chs=' . $width . 'x' . $height . '&chld=' . $level . '|0&cht=qr&chl=' . $urlencoded . '';
    }

    public function checkWithdrawalValidity($request, $user)
    {
        $data = ['success' => false, 'data' => ['is_phone_verified' => true, 'is_google_auth_enabled' => true, 'has_phone' => true], 'message' => __('Validation Error!')];
        try {
            $id = decrypt($request->wallet_id);
        } catch (\Exception $e) {
            $data['message'] = __('Invalid Wallet!');
            return response()->json($data);
        }
        $wallet = MtrWallet::where(['id' => $id, 'user_id' => $user->id])->first();
        if ( !$wallet ) {
            $data['message'] = __('Wallet not found!');
            return response()->json($data);
        }
        if ( $user->phone == '' ) {
            $data['data']['is_phone_verified'] = false;
            $data['data']['has_phone'] = false;
            $data['message'] = __("Please add your phone from settings before Withdrawal.");
            return response()->json($data);
        }
        if ( $user->phone_verified != STATUS_SUCCESS ) {
            $data['data']['is_phone_verified'] = false;
            $data['message'] = __("Please Verify your phone.");
            return response()->json($data);
        }
        if ( !isset($user->user_settings) || ($user->user_settings->gauth_enabled == STATUS_PENDING) ) {
            $data['data']['is_google_auth_enabled'] = false;
            $data['message'] = __("You need to enable google authenticator to send coin from web.");
            return response()->json($data);

        }
        return $data;
    }

    public function send($wallet, $address, $amount, $is_admin = false, $pendingTransaction = null, $authId = null, $message)
    {
        $message = !empty($message) ? $message : 'test message';
        $wallet = Wallet::find($wallet);
        $user = $wallet->user;

        if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {
            $fees = 0;
            $receiverUser = User::where('email', $address)->first();

            if ( empty($receiverUser) ) {
                return ['success' => false, 'message' => __('Not a valid email address to send amount!')];
            }
            if ( $user->id == $receiverUser->id ) {
                return ['success' => false, 'message' => __('You can\'t send to your own wallet!')];
            }
            $receiverWallet = $receiverUser->wallets->where('is_primary', 1)->first();

        } else {
            $walletAddress = $this->isInternalAddress($address);

            if ( empty($walletAddress) ) {
                $receiverWallet = null;
                $receiverUser = null;
                $address_type = ADDRESS_TYPE_EXTERNAL;
                $fees = calculate_fees($amount, settings('send_fees_type'));

            } else {
                $fees = 0;
//                $fees = calculate_fees($amount, settings('send_fees_type'));
                $receiverWallet = $walletAddress->wallet;
                $receiverUser = $walletAddress->wallet->user;
                $address_type = ADDRESS_TYPE_INTERNAL;
                if ( $user->id == $receiverUser->id ) {
                    return ['success' => false, 'message' => __('You can\'t send to your own wallet!')];
                }
            }
        }
        $pendingAmount = WithdrawHistory::where(['wallet_id' => $wallet->id, 'status' => STATUS_PENDING])->sum('amount');

        if ( $is_admin ) {
            $pendingAmount = bcsub($pendingAmount, $amount);
        }

        if ( bccomp(bcadd($amount, $fees), $wallet->balance) > 0 ) {
            return ['success' => false, 'message' => 'Insufficient Balance!'];
        }

        $sendAmount = bcadd($amount, $fees);
        $trans_id = Str::random(32);// we make this same for deposit and withdrawl

        DB::beginTransaction();
        try {
            $common_servcice = new CommonService();
            $affiliate_servcice = new AffiliateRepository();
            $wallet->decrement('balance', $sendAmount);

            $transactionArray = [
                'wallet_id' => $wallet->id,
                'address' => $address,
                'amount' => $amount,
                'address_type' => $address_type,
                'fees' => $fees,
                'transaction_hash' => $trans_id,
                'confirmations' => 0,
                'status' => STATUS_PENDING,
                'message' => $message,
                'receiver_wallet_id' => empty($receiverWallet) ? 0 : $receiverWallet->id
            ];

            $transaction = WithdrawHistory::create($transactionArray);
            $pendingAmount = WithdrawHistory::where(['wallet_id' => $wallet->id, 'status' => STATUS_PENDING])
                ->where('created_at', '>=', Carbon::now()->subDay())
                ->sum('amount');

            if ($pendingAmount < settings('max_send_limit')){
                $transaction->status = STATUS_SUCCESS;
                $transaction->save();
            }

            if ( !empty($receiverWallet) ) {
                $transactionArray = [
                    'address' => $address,
                    'address_type' => $address_type,
                    'amount' => $amount,
                    'fees' => $fees,
                    'transaction_id' => $trans_id,
                    'confirmations' => 0,
                    'status' => STATUS_PENDING,
                    'sender_wallet_id' => $wallet->id,
                    'receiver_wallet_id' => $receiverWallet->id
                ];
             $receive_tr =  DepositeTransaction::create($transactionArray);
                if ($pendingAmount < settings('max_send_limit')) {
                    $receive_tr->status = STATUS_SUCCESS;
                    $receive_tr->save();
                    $receiverWallet->increment('balance', $amount);
                }
            }
            if ( !empty($pendingTransaction) ) {
                $pendingTransaction->status = STATUS_SUCCESS;
            }
//            $bonus = $affiliate_servcice->storeAffiliationHistory($transaction);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->_cancelTransaction($user, $wallet, $address, $amount, $pendingTransaction);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        if ( $address_type == ADDRESS_TYPE_EXTERNAL ) {
            log::info("call external address");
            $response = [];
            $response = $this->external_transfer($address, $amount, $authId, $is_admin, $user->id);
            log::info($response);
            if ($response['status'] === false) {
                DB::rollBack();
                $this->_cancelTransaction($user, $wallet, $address, $amount, $pendingTransaction);
                return [
                    'success' => $response['status'],
                    'message' => $response['message']
                ];
            }
            if ( !empty($pendingTransaction) ) {
                $pendingTransaction->status = STATUS_SUCCESS;
                $pendingTransaction->update();
            }
            if (isset($response['status'])) {
                $transaction->transaction_hash = $response['transaction_id'];
                $bonus = $affiliate_servcice->storeAffiliationHistory($transaction);
            }
            if ( !$transaction->update() ) {
            }

        }
        DB::commit();

        return [
            'success' => true,
            'message' => __('Transaction successful.')
        ];
    }

    // check internal address
    private function isInternalAddress($address)
    {
        return WalletAddressHistory::where('address', $address)->with('wallet')->first();
    }

    // cancel transaction
    private function _cancelTransaction($user, $wallet, $address, $amount, $pendingTransaction)
    {
        if ( !empty($pendingTransaction) ) {
            $pendingTransaction->status = STATUS_REJECTED;
            $pendingTransaction->update();
        }
        //  $mailService = app(MailService::class);
        $userName = $user->first_name . ' ' . $user->last_name;
        $userEmail = $user->email;
        $companyName = isset($default['company']) && !empty($default['company']) ? $default['company'] : __('TCN TCoin Wallet');
        $subject = __(':emailSubject | :companyName', ['emailSubject' => __('Send coin failure'), 'companyName' => $companyName]);
        $data['user'] = $user;
        $data['amount'] = $amount;
        $data['address'] = $address;
        $data['wallet'] = $wallet;
        //  $mailService->send('email.send_coin_failure', $data, $userEmail, $userName, $subject);
    }

    // external transfer
    public function external_transfer($address, $amount, $authId, $isAdmin, $user_id)
    {
        $api = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
//        log::info(json_encode($api));
        $response = $api->verifyAddress($address);
        if ( !$response ) {
            return ['status' => false, 'message' => __('Not a valid address!')];
        }

        $adminId = null;
        $userId = $user_id;
        if ( $isAdmin ) {
            $adminId = $authId;
        } else {
            $userId = $authId;
        }

        $transaction_id = $api->sendToAddress($address, $amount, $userId, $adminId);
        log::info($transaction_id);
        if ( $transaction_id ) {
            return [
                'status' => true,
                'message' => __('Transfer successfully!'),
                'transaction_id' => $transaction_id
            ];
        }
        return [
            'status' => false,
            'message' => __('Failed to send coin!'),
            'nodeMessage' => $api
        ];
    }

    public function acceptPending($wallet, $address, $amount, $fees, $trans, $user_id)
    {
        if ( $trans->address_type == ADDRESS_TYPE_INTERNAL || $trans->address_type == ADDRESS_TYPE_EMAIL ) {
            $transactionArray = [
                'address' => $address,
                'address_type' => $trans->address_type,
                'amount' => $amount,
                'fees' => $fees,
                'transaction_id' => $trans->transaction_id,
                'confirmations' => 0,
                'status' => STATUS_SUCCESS,
                'sender_wallet_id' => $trans->sender_wallet_id,
                'receiver_wallet_id' => $trans->receiver_wallet_id
            ];
            MtrDeposit::create($transactionArray);
            MtrWallet::where(['id' => $trans->receiver_wallet_id])->increment('balance', $amount);
        } elseif ( $trans->address_type == ADDRESS_TYPE_EXTERNAL ) {
            //            $response = $this->external_transfer($address, $amount, Auth::user()->id, true, $user_id);
            //            if ($response['status'] === false) {
            //                DB::rollBack();
            //                $this->_cancelTransaction($user, $wallet, $address, $amount, $pendingTransaction);
            //                return [
            //                    'success' => $response['status'],
            //                    'message' => $response['message']
            //                ];
            //            }
            //            if (!empty($pendingTransaction)) {
            //                $pendingTransaction->status = 'accepted';
            //                $pendingTransaction->update();
            //            }
            //            $transaction->transaction_id = $response['transaction_id'];
            //            if (!$transaction->update()) {
            //            }
        }

    }

    public function isPhoneVerified($user)
    {
        if ( empty($user->phone) || $user->phone_verified == PHONE_IS_NOT_VERIFIED ) {
            return ['success' => false, 'phone_verify' => false, 'message' => __('Please Verify your phone.')];
        } else {
            return ['success' => true, 'phone_verify' => true, 'message' => __('Verified phone.')];
        }
    }

    public function createWithdrawalSecurityCode($transaction)
    {
        $withdrawalSecurityData = [
            'sender_transaction_id' => $transaction->id,
            'mtr_wallet_id' => $transaction->wallet_id,
            'security_code' => 'W-' . $transaction->wallet_id . '-' . time()
        ];

        WithdrawalSecurityCode::create($withdrawalSecurityData);

    }

    public function getUserAccountingData($user_id)
    {

        $data = ['balance' => 0, 'deposits' => 0, 'withdrawals' => 0];
        $data['userinfo'] = MtrWallet::where('user_id', $user_id)->with('user')->first();
        $data['wallets'] = MtrWallet::where('user_id', $user_id)->get();
        $data['userId'] = $data['userinfo']->user_id;
        $data['idStatus'] = $data['userinfo']->user->active_status;
        $data['userName'] = $data['userinfo']->user->first_name . ' ' . $data['userinfo']->user->last_name;
        $data['userFirstName'] = $data['userinfo']->user->first_name;
        $data['userLastName'] = $data['userinfo']->user->last_name;
        $data['userPhoto'] = $data['userinfo']->user->photo;
        $data['userRole'] = $data['userinfo']->user->role;
        $data['userEmail'] = $data['userinfo']->user->email;
        $data['userPhone'] = $data['userinfo']->user->phone;
//            $data['userPhoneVerified'] = $data['userinfo']->user->phone_verified;
        $data['userEmailVerified'] = $data['userinfo']->user->email_verified;

        if ( isset($data['wallets'][0]) ) {
            foreach ($data['wallets'] as $wallet_info) {
                $data['balance'] += $wallet_info->balance;
                $data['deposits'] += MtrDeposit::where(['receiver_wallet_id' => $wallet_info->id, 'status' => STATUS_SUCCESS])->sum(DB::raw('amount'));
                $data['withdrawals'] += MtrWithdrawal::where(['sender_wallet_id' => $wallet_info->id, 'status' => STATUS_SUCCESS])->sum(DB::raw('amount - fees'));
            }
        }
        $data['extra_balance'] = bcsub(bcadd($data['deposits'], $data['withdrawals']), $data['balance']);
        return $data;
    }

    public function checkSuspicious($balance = 0, $deposits = 0, $withdrawals = 0, $suspend = null)
    {
        $extra_balance = bcsub(bcadd($deposits, $withdrawals), $balance);
        if ( $extra_balance < 0 ) {
            if ( !empty($suspend) ) {
                User::where('id', Auth::id())->update([
                    'active_status' => STATUS_SUSPENDED // Force closed for suspicious balance.
                ]);
            }

            return [
                'suspended' => true,
                'extra_balance' => $extra_balance
            ];
        }

        return [
            'suspended' => false,
            'extra_balance' => $extra_balance
        ];
    }

    /**
     * @param $userId
     * @return array
     * This method handles the rest of the referrals except the first level referral
     */
    public function fixReferrals($userId)
    {
        try {
            $parentId = ReferralUser::where('child_id', $userId)->select('parent_id')->get()->first();
            //            dd($parentId);
            $referralLevel = ReferralBonusSettings::all();
            $referralLevel = $referralLevel->count();

            for ($i = 1; $i < $referralLevel; $i++) {
                $newParent = ReferralUser::where('child_id', $parentId->parent_id)->select('parent_id')->get()->first();
                if ( $newParent == null ) {
                    break;
                }
                $referralTree = ReferralTree::where('parent_id', $newParent->parent_id)->get()->first();
                $children = unserialize($referralTree->children);
                if ( isset($children[$i]) ) {
                    $tempArray = $children[$i];
                    array_push($tempArray, $userId);
                    $children[$i] = $tempArray;
                } else {
                    $children[$i] = [$userId];
                }
                $referralTree->children = serialize($children);
                $referralTree->save();

                $parentId = $newParent;

            }

            return ([
                'success' => true,
                'message' => 'We have just sent a verification link on Email with referrals '
            ]);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Something went wrong . Please try again!'
            ];
        }
    }

    /**
     * @param $userId
     * @param $parentId
     * creates referral entries for only for first level
     */
    public function createReferralUser($userId, $parentId)
    {
        try {
            $referralUSer = new ReferralUser;
            $referralUSer->child_id = $userId;
            $referralUSer->parent_id = $parentId;
            $referralUSer->save();

            $treeEntry = ReferralTree::where('parent_id', $parentId)->get()->first();
            if ( !isset($treeEntry) ) {
                $childrenArray = [];

                $treeEntry = new ReferralTree;
                $treeEntry->parent_id = $parentId;
                $childrenArray[0] = [$userId];
                $treeEntry->children = serialize($childrenArray);
                $treeEntry->save();
            } else {
                $childrenArray = unserialize($treeEntry->children);
                $tempArray = $childrenArray[0];
                array_push($tempArray, $userId);
                $childrenArray[0] = $tempArray;
                $treeEntry->children = serialize($childrenArray);
                $treeEntry->save();
            }

        } catch (\Exception $e) {

        }
    }

    public function userRegistration($request, $mail_key)
    {
        try {
            $parentUserId = 0;
            if ( $request->has('ref_code') && $request->ref_code != null ) {
                $parentUser = AffiliationCode::where('code', $request->ref_code)->first();
                if ( !isset($parentUser) ) {
                    $data['success'] = false;
                    $data['message'] = 'Invalid.referral.code.';
                    return $data;
                } else {
                    $parentUserId = $parentUser->user_id;
                }
            }

            DB::beginTransaction();
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => USER_ROLE_USER,
                'active_status' => STATUS_SUCCESS,
                'email_verified' => STATUS_PENDING,
                'phone_verified' => STATUS_PENDING,
                'activity_status' => USER_ACTIVITY_INACTIVE,
            ]);

            if ( $user ) {
                UserSetting::create(['user_id' => $user->id, 'language' => 'en', 'currency' => 'USD']);

                if ( isset($request->country) ) {
                    $country = $request->country;
                } else {
                    $country = null;
                }
                UserInformation::create(['user_id' => $user->id, 'country' => $country]);
                RankingPoint::create([
                    'user_id' => $user->id,
                    'monthly_seller_points' => 0,
                    'yearly_seller_points' => 0,
                    'monthly_consumer_points' => 0,
                    'yearly_consumer_points' => 0,
                    'monthly_bidarator_points' => 0,
                    'yearly_bidarator_points' => 0,
                ]);
                UserActiveInfo::create([
                    'user_id' => $user->id,
                    'expires_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
                    'bids_left' => 0,
                ]);
            }

            UserVerificationCode::create(
                ['user_id' => $user->id
                    , 'type' => 1
                    , 'code' => $mail_key
                    , 'expired_at' => date('Y-m-d', strtotime('+15 days'))
                    , 'status' => STATUS_PENDING]
            );


            $this->generate_wallet($user->id);

            if ( $parentUserId > 0 ) {
                $this->createReferralUser($user->id, $parentUserId);
                $this->fixReferrals($user->id);
            }
            DB::commit();
            $this->sendVerificationMail($user, $mail_key);
            //                $this->sendVerificationSms($request->phone, $randno);
            //                $data['success'] = true;
            //                $data['message'] = 'We have just sent a verification link on Email .';
            //            });
            return [
                'user' => $user,
                'success' => true,
                'message' => __('We have just sent a verification link on Email .')
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => __('Something went wrong . Please try again!')
            ];
        }
    }

    private function generateMtrWallet($userId)
    {
        $mtrWallet = MtrWallet::create(['user_id' => $userId, 'balance' => 0]);
        if ( isset($mtrWallet) ) {
            $walletAddress = WalletAddress::create(['mtr_wallet_id' => $mtrWallet->id, 'address' => $this->generate_address()]);
            return isset($walletAddress) ? true : false;
        }
        return false;
    }

    private function generatePointWallet($userId)
    {
        return (CurrentCashPoint::create(['user_id' => $userId, 'points' => 0])) ? true : false;
    }

    private function generateBidWallet($userId)
    {
        return (BidWallet::create(['user_id' => $userId, 'amount' => 0])) ? true : false;
    }

    private function generate_wallet($user_id)
    {
        $mtrWalletCreate = $this->generateMtrWallet($user_id);
        $pointWalletCreate = $this->generatePointWallet($user_id);
        $bidWalletCreate = $this->generateBidWallet($user_id);
        return ($mtrWalletCreate && $pointWalletCreate && $bidWalletCreate) ? true : false;
    }

    public function sendVerificationMail($user, $mail_key, $mailTemplate = 'email.mailVerify')
    {
        $data['user'] = $user;
        $data['key'] = $mail_key;
        Mail::send($mailTemplate, $data, function ($message) use ($user) {
            $message->to($user->email, $user->first_name . ' ' . $user->last_name)->from('noreply@mtcore.com', __('MTCore'))->subject('Email confirmation');
        });
    }

    public function sendPasswordResetMail($user, $token, $mailTemplate = 'email.password_reset')
    {
        $data['user'] = $user;
        $data['token'] = $token;
        Mail::send($mailTemplate, $data, function ($message) use ($user) {
            $message->to($user->email, $user->first_name . ' ' . $user->last_name)->from('noreply@mtcore.com', __('MTCore'))->subject('Forget Password');
        });
    }

    public function send_validation_check($wallet, $address, $amount)
    {
        $wallet->load('user');
        $user = $wallet->user;
        if ( isset($wallet) && $wallet->balance <= 0 ) {
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Your Wallet Don't have enough balance to withdrawal! "
            ];
        }
        if ( isset($wallet) && $wallet->balance < $amount ) {
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Your Wallet Don't have enough balance to withdrawal! "
            ];
        }

        if ( empty($user->phone) || $user->phone_verified = PHONE_IS_NOT_VERIFIED ) {
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Please Verify your phone."
            ];
        }

        if ( $user->user_settings->gauth_enabled != GOOGLE_AUTH_ENABLED ) {
            return [
                'success' => false,
                'google_auth_verify' => false,
                'message' => "You need to enable google authenticator to send coin from web."
            ];
        }
        //        dd($address,filter_var($address, FILTER_VALIDATE_EMAIL));
        if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {
            $fees = 0;
            $receiverUser = User::where('email', $address)->first();
            if ( empty($receiverUser) ) {
                return [
                    'success' => false,
                    'message' => __('Not a valid email address to send amount!')
                ];
            }

            if ( $user->id == $receiverUser->id ) {
                return [
                    'success' => false,
                    'message' => __('You can\'t send to your own wallet!')
                ];
            }
        } else {
            $walletAddress = $this->isInternalAddress($address);

            if ( empty($walletAddress) ) {
                $api = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
                $response = $api->verifyAddress($address);
                if ( !$response ) {
                    return [
                        'success' => false,
                        'message' => __('Not a valid address!')];
                }
                $receiverUser = null;
                $fees = $this->calculate_fees($amount);

            } else {
                $fees = 0;
                $receiverUser = $walletAddress->wallet->user;

                if ( $user->id == $receiverUser->id ) {
                    return [
                        'success' => false,
                        'message' => __('You can\'t send to your own wallet!')
                    ];
                }
            }
        }

        return [
            'success' => true
        ];
    }

    private function calculate_fees($amount)
    {
        return $amount;
    }

    public function checkwithdrawals($email)
    {
        $withdrawals = Transaction::whereHas('wallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email)
                    ->orWhere(['transaction_id' => $email]);
            });
        })->where(['type' => 'sent'])->get();
        return $withdrawals;
    }

    public function checkdeposit($email)
    {
        $deposit = Transaction::whereHas('receiverWallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email)
                    ->orWhere(['transaction_id' => $email]);
            });
        })->where(['type' => 'receive'])->get();
        return $deposit;
    }

    public function checkbalance($email)
    {
        $walletBalance = MtrWallet::whereHas('user', function ($query) use ($email) {
            $query->where('email', '=', $email);
        })->get();
        return $walletBalance;
    }

    public function user_details($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function transaction_history($email)
    {
        // $transaction = DB::select("SELECT * FROM transactions WHERE wallet_id IN (SELECT id FROM wallet WHERE user_id = (SELECT id FROM users WHERE email = '$email')) or receiver_wallet_id IN (SELECT id FROM wallet WHERE user_id = (SELECT id FROM users WHERE email = '$email'))");
        $transaction = Transaction::whereHas('receiverWallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email)
                    ->orWhere(['transaction_id' => $email]);
            });
        })->orwhereHas('wallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email)
                    ->orWhere(['transaction_id' => $email]);
            });
        })->get();
        return $transaction;
    }

    public function pending_transaction($email)
    {
        $pending_transaction = PendingTransaction::whereHas('receiverWallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email);
            });
        })->orwhereHas('wallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email);
            });
        })->get();
        return $pending_transaction;
    }

    public function wallet_address($email)
    {
        $wallet_address = WalletAddress::whereHas('wallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email);
            });
        })->get();
        return $wallet_address;
    }

    public function tolerence_balance($email)
    {
        $tolerence_balance = WalletToleranceBalance::whereHas('wallet', function ($query) use ($email) {
            $query->whereHas('user', function ($where) use ($email) {
                $where->where('email', '=', $email);
            });
        })->get();

        return $tolerence_balance;
    }

    private function sendTransactionMail($sender_user, $mailTemplet, $receiver_user, $amount, $emailSubject)
    {
        $mailService = app(MailService::class);
        $userName = $sender_user->first_name . ' ' . $sender_user->last_name;
        $userEmail = $sender_user->email;
        $companyName = isset($default['company']) && !empty($default['company']) ? $default['company'] : __('TCN TCoin Wallet');
        $subject = __(':emailSubject | :companyName', ['emailSubject' => $emailSubject, 'companyName' => $companyName]);
        $data['data'] = $sender_user;
        $data['anotherUser'] = $receiver_user;
        $data['amount'] = $amount;
        $mailService->send($mailTemplet, $data, $userEmail, $userName, $subject);
    }

    private function sendExternalTransactionMail($sender_user, $mailTemplet, $address, $amount, $emailSubject)
    {
        $mailService = app(MailService::class);
        $userName = $sender_user->first_name . ' ' . $sender_user->last_name;
        $userEmail = $sender_user->email;
        $companyName = isset($default['company']) && !empty($default['company']) ? $default['company'] : __('TCN TCoin Wallet');
        $subject = __(':emailSubject | :companyName', ['emailSubject' => $emailSubject, 'companyName' => $companyName]);
        $data['data'] = $sender_user;
        $data['address'] = $address;
        $data['amount'] = $amount;
        $mailService->send($mailTemplet, $data, $userEmail, $userName, $subject);
    }

    private function sendVerificationSms($phone, $randno)
    {
        $smsText = 'Your ' . allsetting()['app_title'] . ' verification code is here ' . $randno;
        app(SmsService::class)->send($phone, $smsText);
    }

    /**
     * @param $req
     * @return array|string
     */
//    public function getReturnDoman($req)
//    {
//        $reqUrl = $req->fullUrl();
//        $referer = explode('?', $reqUrl);
//
//        if ( count($referer) > 1 ) {
//            $referer = explode('=', $referer[1]);
//            $returnDomain = $referer[0];
//        } else {
//            $returnDomain = explode('/', $referer[0]);
//            $returnDomain = $returnDomain[2];
//            $returnDomain = explode('.', $returnDomain);
//            $returnDomain = $returnDomain[0];
//        }
//
//        if ( $returnDomain == 'tab' ) {
//            $returnDomain = 'mtr-wallet';
//        }
//
//        return $returnDomain;
//    }


    // user deposit history
    public function depositTransactionHistories($user_id = null, $status = null, $wallet_id = null, $address_type = null, $transaction_id = null)
    {
        $histories = DepositeTransaction::join('wallets', 'wallets.id', 'deposite_transactions.receiver_wallet_id')
         ->select('wallets.*','deposite_transactions.*');
        if ( !empty($status) )
            $histories = $histories->where('deposite_transactions.status', $status);
        if ( !empty($wallet_id) )
            $histories = $histories->where('wallets.id', $wallet_id);
        if ( !empty($address_type) )
            $histories = $histories->where('deposite_transactions.address_type', $address_type);
        if ( !empty($transaction_id) )
            $histories = $histories->where('deposite_transactions.transaction_id', $transaction_id);
        if ( !empty($user_id) )
            $histories = $histories->where('wallets.user_id', $user_id);

        return $histories;
    }

    // user withdrawal history
    public function withdrawTransactionHistories($user_id = null, $status = null, $wallet_id = null, $address_type = null, $transaction_id = null)
    {
        $histories = WithdrawHistory::join('wallets', 'wallets.id', 'withdraw_histories.wallet_id')
            ->select('wallets.*','withdraw_histories.*');
        if ( !empty($status) )
            $histories = $histories->where('withdraw_histories.status', $status);
        if ( !empty($wallet_id) )
            $histories = $histories->where('wallets.id', $wallet_id);
        if ( !empty($address_type) )
            $histories = $histories->where('withdraw_histories.address_type', $address_type);
        if ( !empty($transaction_id) )
            $histories = $histories->where('withdraw_histories.transaction_id', $transaction_id);
        if ( !empty($user_id) )
            $histories = $histories->where('wallets.user_id', $user_id);

        return $histories;
    }
}
