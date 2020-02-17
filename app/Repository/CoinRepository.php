<?php
/**
 * Created by PhpStorm.
 * User: rana
 * Date: 8/30/17
 * Time: 5:19 PM
 */

namespace App\Repository;

use App\Http\Services\CommonService;
use App\Model\User\AffiliationCode;
use App\Model\User\AffiliationHistory;
use App\Model\User\BuyCoinHistories;
use App\Model\User\DepositeTransaction;
use App\Model\User\ReferralUser;
use App\Model\User\Wallet;
use App\Model\User\WalletAddressHistory;
use App\Model\User\WithdrawHistory;
use App\Services\BitCoinApiService;
use App\User;
use App\Services\Logger;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CoinRepository
{
    //create wallet
    public function buyCoin($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');

            if (isset(\GuzzleHttp\json_decode($url, true)['BTC'])) {
                $coin_price_doller = bcmul($request->coin, settings('coin_price'));
                $coin_price_btc = bcmul(\GuzzleHttp\json_decode($url, true)['BTC'], $coin_price_doller);
                $coin_price_btc = number_format($coin_price_btc, 8);

                if ($request->payment_type == BTC) {
                    $btc_transaction = new BuyCoinHistories();
                    $btc_transaction->address = settings('admin_coin_address');
                    $btc_transaction->type = BTC;
                    $btc_transaction->user_id = Auth::id();
                    $btc_transaction->coin = $request->coin;
                    $btc_transaction->doller = $coin_price_doller;
                    $btc_transaction->btc = $coin_price_btc;
                    $btc_transaction->save();

                    $response = ['success' => true, 'message' => __('Request submitted successful,please send BTC with this address')];
                } elseif ($request->payment_type == CARD) {
                    $common_servie = new CommonService();
                    $all_req = $request->all();
                    $all_req['btn_amount'] = $coin_price_btc;
                    $all_req['total_coin_price_in_dollar'] = $coin_price_doller;
                    $trans = $common_servie->make_transaction((object)$all_req);
                    if ($trans['success']) {
                        DB::beginTransaction();
                        try {
                            $btc_transaction = new BuyCoinHistories();
                            $btc_transaction->type = CARD;
                            $btc_transaction->user_id = Auth::id();
                            $btc_transaction->coin = $request->coin;
                            $btc_transaction->address = $trans['data']->networkTransactionId;
                            $btc_transaction->doller = $coin_price_doller;
                            $btc_transaction->btc = $coin_price_btc;
                            $btc_transaction->status = STATUS_SUCCESS;
                            $btc_transaction->save();

                            //  add  coin on balance //
                            $default_wallet = Wallet::where('user_id', Auth::id())->where('is_primary', 1)->first();
                            $default_wallet->balance = $default_wallet->balance + $request->coin;
                            $default_wallet->save();

                            DB::commit();
                            $response = ['success' => true, 'message' => __('Coin purchased successfully')];

                            // all good
                        } catch (\Exception $e) {

                            DB::rollback();
                            $response = ['success' => false, 'message' => __('Something went wrong')];
                            return $response;
                        }
                    } else {
                        $response = ['success' => false, 'message' => $trans['message']];
                    }
                } elseif ($request->payment_type = BANK_DEPOSIT) {
                    $btc_transaction = new BuyCoinHistories();
                    $btc_transaction->type = BANK_DEPOSIT;
                    $btc_transaction->address = 'N/A';
                    $btc_transaction->user_id = Auth::id();
                    $btc_transaction->doller = $coin_price_doller;
                    $btc_transaction->btc = $coin_price_btc;
                    $btc_transaction->coin = $request->coin;
                    $btc_transaction->bank_id = $request->bank_id;
                    $btc_transaction->bank_sleep = uploadFile($request->file('sleep'), IMG_SLEEP_PATH);
                    $btc_transaction->save();

                    $response = ['success' => true, 'message' => __('Request submitted successful,Please wait for admin approval')];
                }
            } else {
                $response = ['success' => false, 'message' => __('Invalid request')];
            }

        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => __('Something went wrong')];
            return $response;
        }

        return $response;
    }

}
