<?php
/**
 * Created by PhpStorm.
 * User: biplab
 * Date: 4/3/19
 * Time: 11:33 AM
 */

namespace App\Http\Services;

use App\Model\User\DepositeTransaction;
use App\Model\User\Referral;
use App\Model\User\ReferralUser;
use App\Model\User\UserVerificationCode;
use App\Model\User\Wallet;
use App\Model\User\WithdrawHistory;
use App\Services\BitCoinApiService;
use App\Taggable;
use App\User;
use Braintree;
use Carbon\Carbon;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Horizon\SupervisorCommands\Balance;
use  \App\Tag;

class CommonService
{

    public function tag($post,$request)
    {
        $tags = explode(',',$request->tags);
//        $tagable = Tag::whereIn(['name'=>$tags])->pluck('id');
        Taggable::where(['taggable_id'=>$post->id])->delete();
        foreach ($tags as $tag){
            $tags_data = new Tag();
            if (Tag::where(['name'=>$tag,'slug'=>$tag])->exists())
                $tags_data = Tag::where(['name'=>$tag,'slug'=>$tag])->first();
            $tags_data->name = $tag;
            $tags_data->slug = $tag;
            $tags_data->save();
            Taggable::create(['tag_id'=>$tags_data->id,'taggable_id'=>$post->id]);
        }



        //$post->attachTag('tag3');
    }
    public function send_validation_check($wallet, $address, $amount)
    {
        $wallet->load('user');
        $user = $wallet->user;
        if(isset($wallet) && $wallet->balance<=0){
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Your Wallet Don't have enough balance to withdrawal! "
            ];
        }
        if(isset($wallet) && $wallet->balance<$amount){
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Your Wallet Don't have enough balance to withdrawal! "
            ];
        }

        if (empty($user->phone) || $user->phone_verified = PHONE_IS_NOT_VERIFIED) {
            return [
                'success' => false,
                'phone_verify' => false,
                'message' => "Please Verify your phone."
            ];
        }

        if ($user->user_settings->gauth_enabled != GOOGLE_AUTH_ENABLED) {
            return [
                'success' => false,
                'google_auth_verify' => false,
                'message' => "You need to enable google authenticator to send coin from web."
            ];
        }
        //        dd($address,filter_var($address, FILTER_VALIDATE_EMAIL));
        if (filter_var($address, FILTER_VALIDATE_EMAIL)) {
            $fees = 0;
            $receiverUser = User::where('email', $address)->first();
            if (empty($receiverUser)) {
                return [
                    'success' => false,
                    'message' => 'Not a valid email address to send amount!'
                ];
            }

            if ($user->id == $receiverUser->id) {
                return [
                    'success' => false,
                    'message' => __('You can\'t send to your own wallet!')
                ];
            }
        } else {
            $walletAddress = $this->isInternalAddress($address);
            if (empty($walletAddress)) {
                $tcnCredentials = allsetting(['tcn_host', 'tcn_port', 'tcn_user', 'tcn_pass']);
                $api = new BitCoinApiService($tcnCredentials['tcn_user'], $tcnCredentials['tcn_pass'], $tcnCredentials['tcn_host'], $tcnCredentials['tcn_port']);
                $response = $api->verifyAddress($address);
                if (!$response) {
                    return [
                        'success' => false,
                        'message' => __('Not a valid address!')];
                }
                $receiverUser = null;
                $fees = calculate_fees($amount, 'sndcnfeemethod', 'sndcnfeepercent', 'sndcnfeefixed');

            } else {
                $fees = 0;
                $receiverUser = $walletAddress->wallet->user;

                if ($user->id == $receiverUser->id) {
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

    public function make_transaction($request)
    {
        try {
            $user = Auth::user();
            $getway = new Braintree\Gateway([
                'environment' => settings('braintree_environment')
                , 'merchantId' => settings('braintree_merchant_id')
                , 'publicKey' => settings('braintree_public_key')
                , 'privateKey' => settings('braintree_private_key')
            ]);

            $amount = $request->total_coin_price_in_dollar;
            $nonce = $request->payment_method_nonce;

            $result = $getway->transaction()->sale([
                'amount' => $amount,
                'paymentMethodNonce' => $nonce,
                'customer' => [
                    'firstName' => $user->first_name,
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                ],
                'options' => [
                    'submitForSettlement' => true
                ]
            ]);


            if ($result->success) {
                $transaction = $result->transaction;
                return ['success'=>true,'data'=>$transaction,'message'=>__('Transaction successful.')];
            } else {
                return ['success'=>false,'data'=>[],'a'=>'a','message'=>$result->message];
            }
        } catch (\Exception $e) {
            return ['success'=>false,'data'=>[],'message'=> __('Please check your braintree credential')];
        }
    }


    // user deposit
    public function AuthUserDiposit($start_date, $end_date, $type = null)
    {
        $data = DepositeTransaction::join('wallets', 'wallets.id', 'deposite_transactions.receiver_wallet_id');
        if (!empty($type) && ($type == 'week')) {
            $data = $data->select(
                DB::raw('DAYNAME(deposite_transactions.created_at) as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );

        } elseif (!empty($type) && ($type == 'month')) {
            $data = $data->select(
                DB::raw('DATE_FORMAT(deposite_transactions.created_at, "%d-%b")  as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );

        } else {
            $data = $data->select(
                DB::raw('MONTHNAME(deposite_transactions.created_at) as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );
        }

        $data = $data->where('deposite_transactions.status', STATUS_SUCCESS)
            ->where('wallets.user_id', Auth::id())
            ->whereBetween('deposite_transactions.created_at', [$start_date, $end_date])
            ->groupBy('month')
            ->pluck('balance', 'month');

        return $data;
    }

    // user withdrawal history
    public function AuthUserWithdraw($start_date, $end_date, $type = null)
    {
        $data = WithdrawHistory::join('wallets', 'wallets.id', 'withdraw_histories.wallet_id');
        if (!empty($type) && ($type == 'week')) {

            $data = $data->select(
                DB::raw('DAYNAME(withdraw_histories.created_at) as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );

        } elseif (!empty($type) && ($type == 'month')) {

            $data = $data->select(
                DB::raw('DATE_FORMAT(withdraw_histories.created_at, "%d-%b")  as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );


        } else {
            $data = $data->select(
                DB::raw('MONTHNAME(withdraw_histories.created_at) as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );
        }
        $data = $data->where('withdraw_histories.status', STATUS_SUCCESS)
            ->where('wallets.user_id', Auth::id())
            ->whereBetween('withdraw_histories.created_at', [$start_date, $end_date])
            ->groupBy('month')
            ->pluck('balance', 'month');

        return $data;
    }

    // all deposit and withdrawal data for chart
    public function userDiposit($start_date, $end_date, $type = null)
    {
        $data = DepositeTransaction::join('wallets', 'wallets.id', 'deposite_transactions.receiver_wallet_id');
        if (!empty($type) && ($type == 'week')) {

            $data = $data->select(
                DB::raw('DAYNAME(deposite_transactions.created_at) as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );

        } elseif (!empty($type) && ($type == 'month')) {
            $data = $data->select(
                DB::raw('DATE_FORMAT(deposite_transactions.created_at, "%d-%b")  as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );

        } else {
            $data = $data->select(
                DB::raw('MONTHNAME(deposite_transactions.created_at) as month'),
                DB::raw('SUM(deposite_transactions.amount) as balance')
            );
        }

        $data = $data->where('deposite_transactions.status', STATUS_SUCCESS)
//            ->where('wallets.user_id',Auth::id())
            ->whereBetween('deposite_transactions.created_at', [$start_date, $end_date])
            ->groupBy('month')
            ->pluck('balance', 'month');

        return $data;
    }

    // user withdraw
    public function userWithdraw($start_date, $end_date, $type = null)
    {
        $data = WithdrawHistory::join('wallets', 'wallets.id', 'withdraw_histories.wallet_id');
        if (!empty($type) && ($type == 'week')) {

            $data = $data->select(
                DB::raw('DAYNAME(withdraw_histories.created_at) as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );

        } elseif (!empty($type) && ($type == 'month')) {

            $data = $data->select(
                DB::raw('DATE_FORMAT(withdraw_histories.created_at, "%d-%b")  as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );


        } else {
            $data = $data->select(
                DB::raw('MONTHNAME(withdraw_histories.created_at) as month'),
                DB::raw('SUM(withdraw_histories.amount) as balance')
            );
        }
        $data = $data->where('withdraw_histories.status', STATUS_SUCCESS)
//            ->where('wallets.user_id',Auth::id())
            ->whereBetween('withdraw_histories.created_at', [$start_date, $end_date])
            ->groupBy('month')
            ->pluck('balance', 'month');

        return $data;
    }


    public function distributeAffiliationBonus($software_price, $software_id)
    {
        $level = ReferralBonusSettings::all()->count();
        $user_id = $this->id;
        $user = $user_id;
        for ($i = 0; $i < $level; $i++) {
            $parent = ReferralUser::where('child_id', $user_id)->get()->first();
            if($parent == null) {
                break;
            }
// seller ranking points history add
            $seller_ranking_point = new SellerRankingPoint;

            $seller_ranking_point->parent_id = $parent->parent_id;
            $seller_ranking_point->child_id = $user;
            if($i == 1){
                $sellsDirector = User::find($parent->parent_id);
                if($sellsDirector->isSellerDirector()){
                    $seller_ranking_point->points = $software_price * (ReferralBonusSettings::where('level', $i+1)->get()->first()->seller_ranking_point_seller_director);
                }else{
                    $seller_ranking_point->points = $software_price * (ReferralBonusSettings::where('level', $i+1)->get()->first()->seller_ranking_point);
                }
            }else{
                $seller_ranking_point->points = $software_price * (ReferralBonusSettings::where('level', $i+1)->get()->first()->seller_ranking_point);
            }
            $seller_ranking_point->level = $i+1;
            $seller_ranking_point->software_id = $software_id;
            $seller_ranking_point->save();

// cash points history add
            $cash_point = new CashPoint;
            $cash_point->parent_id = $parent->parent_id;
            $cash_point->child_id = $user;
            $percentage = ReferralBonusSettings::where('level', $i + 1)->get()->first()->cash_point_percentage;
            if($percentage > 0) {
                $cash_point->points = (float)($software_price * ($percentage / 100));
            } else {
                $cash_point->points = 0;
            }
            $cash_point->level = $i+1;

            $cash_point->save();

//current cash points add
            $currentCash = CurrentCashPoint::where('user_id', $parent->parent_id)->get()->first();
            $currentCash->points = $cash_point->points + $currentCash->points;
            $currentCash->save();

//sells_director_distribute
            if($parent->parent_user->isSellerDirector()) {
                $sells_cash_point = new CashPoint;
                $sells_cash_point->parent_id = $parent->parent_id;
                $sells_cash_point->child_id = $user;

                $percentage = ReferralBonusSettings::where('level', $i + 1)->get()->first()->sells_director_bonus;

                if($percentage > 0) {
                    $sells_cash_point->points = (float)($software_price * ($percentage / 100));
                } else {
                    $sells_cash_point->points = 0;
                }
                $sells_cash_point->level = $i + 1;
                $sells_cash_point->save();

                $currentCash->points =$sells_cash_point->points + $currentCash->points;
                $currentCash->save();
            }

//seller ranking points add in current ranking points table
            $rankingPoints = RankingPoint::where('user_id', $parent->parent_id)->get()->first();
            $rankingPoints->monthly_seller_points = $rankingPoints->monthly_seller_points + $seller_ranking_point->points;
            $rankingPoints->yearly_seller_points = $rankingPoints->yearly_seller_points + $seller_ranking_point->points;
            $rankingPoints->save();

            $user_id = $parent->parent_id;
        }

    }


    public function affiliationBonus($withdrawal) {
        $data['success'] = false;
        $wallet = Wallet::find($withdrawal->wallet_id);

        DB::beginTransaction();

        try {
            if (!empty($wallet)){
                $child = Referral::where('user_id',$wallet->user_id)->first();
                if (!empty($child)){

                  //  $commision = ($withdrawal->fees*settings('referral_commission_percentage'))/100;
                    $commision = bcdiv(bcmul($withdrawal->fees, settings('referral_commission_percentage')), '100');
                    Log::info($commision);
                    Log::info(settings('referral_commission_percentage'));
                    Log::info(json_encode($withdrawal));
                    $bonus = new Bonus();
                    $bonus->user_id = $child->parent_user_id;
                    $bonus->child_id = $wallet->user_id;
                    $bonus->withdrawal_id = $withdrawal->id;
                    $bonus->commission_percentage =settings('referral_commission_percentage');
                    $bonus->commission =  $commision;
                    $bonus->save();
                    Wallet::where('user_id',$bonus->user_id)->where('is_primary',STATUS_SUCCESS)->increment('balance', $commision);
                    $data['success'] = true;
                }
            }

            DB::commit();
            // all good
        } catch (\Exception $e) {
            $data['success'] = false;
            DB::rollback();
            // something went wrong
        }




        return $data;
    }

    // check id
    public function checkValidId($id){
        try {
            $id = decrypt($id);
        } catch (\Exception $e) {
            return ['success'=>false];
        }
        return $id;
    }

    // mail verification process
    public function mailVarification($request)
    {
        try {
            $uvc = UserVerificationCode::join('users','users.id','=', 'user_verification_codes.user_id')
                ->where(['user_verification_codes.code' => $request->access_code,
                    'users.email'=>$request->email, 'user_verification_codes.status' => STATUS_PENDING])
                ->where('user_verification_codes.expired_at', '>=', date('Y-m-d'))
                ->first();
            if ($uvc) {
                UserVerificationCode::where(['id' => $uvc->id])->update(['status' => STATUS_SUCCESS]);
                User::where(['id' => $uvc->user_id])->update(['is_verified' => STATUS_SUCCESS]);

                return [
                    'success' => true,
                    'message' => __('Email verification successfull.')
                ];
            } else {
                return [
                    'success' => false,
                    'message' => __('Verification code expired or not found!')
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('Invalid request . Please try again!')
            ];
        }
    }

    // send forgot password code
    public function sendResetPasswordCode($request)
    {
        $user = User::where(['email' => $request->email])->first();

        if ($user) {
            $alreadyCode = UserVerificationCode::where(['user_id' =>$user->id,'status' => STATUS_PENDING])->first();
            if($alreadyCode) {
                $alreadyCode->update(['status' => STATUS_SUCCESS]);
            }
            $token = randomNumber(6);
            UserVerificationCode::create([
                'user_id' => $user->id,
                'code' => $token,
                'expired_at' => date('Y-m-d', strtotime('+10 days')),
                'status' => STATUS_PENDING
            ]);
            $user_data = [
                'email' => $user->email,
                'name' => $user->first_name.' '.$user->last_name,
                'token' => $token,
            ];
            try {
                Mail::send('email.password_reset', $user_data, function ($message) use ($user) {
                    $message->to($user->email, $user->name)->subject('Forgot Password');
                });
            } catch (\Exception $e) {
                $response = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
                return $response;
            }
            $response = [
                'message' => 'Mail sent Successfully to ' . $user->email . ' with password reset Code.',
                'success' => true
            ];

        } else {
            $response = [
                'message' => __('Sorry! The email could not be found'),
                'success' => false
            ];
        }
        return $response;
    }

    public function resetForgotPassword($request)
    {
        try {
            $vf_code = UserVerificationCode::join('users','users.id','=', 'user_verification_codes.user_id')
                ->where(['user_verification_codes.code' => $request->access_code,
                    'users.email'=>$request->email, 'user_verification_codes.status' => STATUS_PENDING])
                ->first();

            if (isset($vf_code)) {
                User::where(['id' => $vf_code->user_id])->update(['password' => bcrypt($request->password)]);
                UserVerificationCode::where(['id' => $vf_code->id])->update(['status' => STATUS_SUCCESS]);
                $data['success'] = true;
                $data['message'] = __('Password reset successfully');
            } else {
                $data['success'] = false;
                $data['message'] = __('Reset code not valid.');
            }
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();

            return $data;
        }

        return $data;
    }

}