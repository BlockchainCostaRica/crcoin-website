<?php

namespace App\Http\Controllers\Api;


use App\Model\User\BuyCoinHistories;
use App\Model\User\DepositeTransaction;
use App\Model\User\Wallet;
use App\Model\User\WalletAddressHistory;
use App\Services\BitCoinApiService;
use App\Services\MailService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function Sodium\increment;

class WalletNotifier extends Controller
{
    public function walletNofity(Request $request)
    {
        Log::info('notity called');
        Log::info($request->all());

        $coinType = strtoupper($request->coin_type);
        if ($coinType != 'BTC') {
            return response()->json(['message' => __('Invaild Coin!')]);
        }
        $transactionId = $request->transaction_id;
        $coinservice =  new BitCoinApiService(settings('coin_api_user'), settings('coin_api_pass'),settings('coin_api_host'), settings('coin_api_port'));
        $transaction = $coinservice->getTranscation($transactionId);

        if($transaction) {
            $details = $transaction['details'];

            foreach ($details as $data) {
                if ($data['category'] = 'receive') {
                    $address[] = $data['address'];
                    $amount[] = $data['amount'];
                }
            }
            if (empty($address) || empty($amount)) {
                return response()->json(['message' => __('This is a withdraw transaction hash')]);
            }
            DB::beginTransaction();
            try {
                $wallets = WalletAddressHistory::whereIn('address', $address)->get();

                if ($wallets->isEmpty()) {
                    return response()->json(['message' => __('Notify Unsuccessful. Address not found!')]);
                }
                if (!$wallets->isEmpty()) {
                    foreach ($wallets as $wallet) {
                        foreach ($address as $key => $val) {
                            if ($wallet->address == $val) {
                                $currentAmount = $amount[$key];
                            }
                        }
                        $inserts [] = [
                            'address' => $wallet->address,
                            'receiver_wallet_id' => $wallet->wallet_id,
                            'address_type' => 1,
                            'amount' => $currentAmount,
//                            'type' => 'receive',
                            'status' => STATUS_PENDING,
                            'transaction_id' => $transactionId,
                            'confirmations' => $transaction['confirmations'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ];
                    }
                }

                $response = [];
                if (!empty($inserts)) {
                    foreach ($inserts as $insert) {
                        $has_transaction = DepositeTransaction::where(['transaction_id' => $insert['transaction_id'], 'address' => $insert['address']])->count();
                        if (!$has_transaction) {
                            try {
                                DepositeTransaction::insert($insert);
                            } catch (\Exception $e) {
                                return response()->json([
                                    'message' => __('Transaction Hash is already in DB.'),
                                ]);
                            }
                            $response[] = [
                                'transaction_id' => $insert['transaction_id'],
                                'address' => $insert['address'],
                                'success' => true
                            ];
                        } else {
                            $response [] = [
                                'transaction_id' => $insert['transaction_id'],
                                'address' => $insert['address'],
                                'success' => false
                            ];
                        }
                    }
                }
                Log::info('notyfy- ');
                Log::info($response);
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                $response [] = [
                    'transaction_id' => '',
                    'address' => '',
                    'success' => false
                ];
            }

            if (empty($response)) {
                return response()->json([
                    'message' => __('Notified Unsuccessful.'),
                ]);
            }

            return response()->json([
                'response' => $response,
            ]);
        }

        return response()->json(['message' => __('Not a valid transaction.')]);
    }

    public function notifyConfirm(Request $request)
    {
        Log::info('notify confirmed called');
        Log::info($request->all());

        $number_of_confirmation = 1;
        $transactions = $request->transactions;
        if(!empty($transactions))
        {
            foreach ($transactions as $transaction)
            {
                if($transaction['category'] == 'receive')
                {
                    $is_confirmed = false;
                    $transactionId = $transaction['txid'];
                    $address = $transaction['address'];
                    $pendingTransaction = DepositeTransaction::where(['transaction_id' => $transactionId, 'address' => $address])->first();
                    if(!empty($pendingTransaction))
                    {
                        $confirmation = $transaction['confirmations'];
                        Log::info('confirmation-> '.$confirmation);
                        if($confirmation >= $number_of_confirmation && $pendingTransaction->status != STATUS_SUCCESS)
                        {
                            DB::beginTransaction();

                            try {
                                $amount = $pendingTransaction->amount;
                                Log::info('Wallet-Notify');
                                Log::info('Received Amount: '. $amount);
                                Log::info('Balance Before Update: '. $pendingTransaction->receiverWallet->balance);
                                $pendingTransaction->receiverWallet->increment('balance', $amount);
                                Log::info('Balance After Update: '. $pendingTransaction->receiverWallet->balance);
                                $update = DepositeTransaction::where(['id' => $pendingTransaction->id, 'status' => STATUS_PENDING])->update(['confirmations' => $confirmation, 'status' => STATUS_SUCCESS]);
                                Log::info('Wallet-Notify executed');
                                if (!$update) {
                                    DB::rollback();
                                    $response[] = [
                                        'txid' => $transactionId,
                                        'is_confirmed' => false,
                                        'message' => __('Already deposited.')
                                    ];

                                    $logText = [
                                        'walletID' => $pendingTransaction->receiverWallet->id,
                                        'transactionID' => $transactionId,
                                        'amount' => $amount,
                                    ];
                                    Log::info('Wallet-Notify-Failed');
                                    Log::info(json_encode($logText));

                                    return response()->json($response);
                                }
                            } catch (\Exception $e) {
                                DB::rollback();
                                $response[] = [
                                    'txid' => $transactionId,
                                    'is_confirmed' => false,
                                    'message' => __('Already deposited.')
                                ];

                                $logText = [
                                    'walletID' => $pendingTransaction->receiverWallet->id,
                                    'transactionID' => $transactionId,
                                    'amount' => $amount,
                                ];
                                Log::info('Wallet-Notify-Failed');
                                Log::info(json_encode($logText));

                                return response()->json($response);
                            }
                            DB::commit();

                            $is_confirmed = true;
                            $response[] = [
                                'txid' => $transactionId,
                                'is_confirmed' => $is_confirmed,
                                'current_confirmation' => $confirmation
                            ];
                        }
                        else
                        {
                            if($confirmation >= $number_of_confirmation && $pendingTransaction->status == STATUS_SUCCESS)
                            {
                                $pendingTransaction->update(['confirmations' => $confirmation]);
                            }
                            elseif ($confirmation < $number_of_confirmation && $pendingTransaction->status == STATUS_PENDING)
                            {
                                $pendingTransaction->update(['confirmations' => $confirmation]);
                            }

                            $response[] = [
                                'txid' => $transactionId,
                                'is_confirmed' => $is_confirmed,
                                'current_confirmation' => $confirmation
                            ];
                        }
                    }
                    else
                    {
                        $response[] = [
                            'txid' => $transactionId,
                            'is_confirmed' => $is_confirmed,
                            'message' => __('Transaction Id is not available')
                        ];
                    }
                }
            }
        }
        else{
            $response [] = [
                'message' => __('No Transaction Found')
            ];
        }

        if (!isset($response)) {
            return response()->json(['status' => false]);
        }

        return response()->json($response);
    }


}
