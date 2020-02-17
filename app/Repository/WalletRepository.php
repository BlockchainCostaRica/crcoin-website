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


class WalletRepository
{
    // user available balance
    public function availableBalance($user_id)
    {
        $balance = getUserBalance($user_id);
        $data['available_coin'] = number_format($balance['available_coin'],8);
        $data['available_usd'] = number_format($balance['available_used'],8);
        $data['coin_name'] = settings('coin_name');

        return $data;
    }

    // user wallet list
    public function walletList($user_id)
    {
        $wallets = Wallet::where(['user_id' => $user_id])->orderBy('id', 'desc')->get();
        if (isset($wallets[0])) {
            foreach ($wallets as $wallet) {
                $wallet->address = $this->walletAddressList($wallet->id);
                $wallet->encrypt_id = encrypt($wallet->id);
            }
            $data = [
                'success' => true,
                'wallet_list' => $wallets,
                'message' => __('Data get successfully')
            ];
        } else {
            $data = [
                'success' => false,
                'wallet_list' => [],
                'message' => __('No data found')
            ];
        }

        return $data;
    }

    // wallet address list
    public function walletAddressList($wallet_id)
    {
        $addressList = [];
        $address = WalletAddressHistory::where(['wallet_id' => $wallet_id])->orderBy('id', 'desc')->get();
        if (isset($address[0])) {
            foreach ($address as $adrs) {
                $addressList[] = $adrs->address;
            }
        }

        return $addressList;
    }

    //create wallet
    public function createNewWallet($request)
    {
        $response = ['success' => false, 'message' => __('Invalid request')];
        try {
            $data = [
                'user_id' => Auth::id(),
                'name' => $request->name,
                'status' => STATUS_SUCCESS,
                'balance' => 0
            ];
            $createWallet = Wallet::create($data);
            if ($createWallet) {
                $this->generateNewAddress($createWallet->id);

                $response = ['success' => true, 'message' => __('New wallet created successfully')];
            }

        } catch(\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
        }

        return $response;
    }

    // generate new wallet address
    public function generateNewAddress($wallet_id)
    {
        $response = ['success' => false, 'address_list' =>[], 'message' => __('Invalid request')];
        try {
            $wallet = new \App\Services\wallet();
            $api = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
            $address = $api->getNewAddress();
            $generate = $wallet->AddWalletAddressHistory($wallet_id,$address);
            if ($generate) {

                $response = ['success' => true, 'address_list' => $this->walletAddressList($wallet_id), 'message' => __('Address generated successfully')];
            }

        } catch (\Exception $e) {
            $response = ['success' => false, 'address_list' =>[], 'message' => $e->getMessage()];
        }

        return $response;
    }

    // wallet transaction history
    public function walletTransactionHistory($wallet_id)
    {
        $response = ['success' => false, 'transaction_list' =>[], 'message' => __('Invalid request')];
        $id = app(CommonService::class)->checkValidId($wallet_id);

        if (is_array($id)) {
            $response = ['success' => false, 'message' => __('Item not found')];
            return response()->json($response);
        }
        $transactions = DepositeTransaction::where('sender_wallet_id', $id)
            ->orWhere('receiver_wallet_id', $id)
            ->orderBy('id', 'Desc')
            ->get();

        if(isset($transactions[0])) {
            foreach ($transactions as $tran) {
                $tran->fees = isset($tran->fees) ? $tran->fees : 0 ;
                $tran->sender_wallet_name = isset($tran->sender_wallet_id) ? $tran->senderWallet->name : '' ;
                $tran->receiver_wallet_name = isset($tran->receiver_wallet_id) ? $tran->receiverWallet->name : '' ;
                $tran->address_type = $tran->address_type == 'internal_address' ? addressType(ADDRESS_TYPE_EXTERNAL) : addressType($tran->address_type) ;
                $tran->transaction_type = $tran->receiver_wallet_id == $id ? DEPOSIT : WITHDRAWAL ;
                $tran->status_text = deposit_status($tran->status);
            }
            $response = ['success' => true, 'transaction_list' => $transactions, 'message' => __('Data get successfully')];
        } else {
            $response = ['success' => false, 'transaction_list' =>[], 'message' => __('Data not found')];
        }

        return $response;
    }

    // all activity history
    public function allActivityList()
    {
        $response = ['success' => false, 'activity_list' =>(object)[], 'message' => __('Invalid request')];

        $transactions = DB::select("select wallets.name, case when sender_wallet_id=wallets.id then '2'
            when receiver_wallet_id=wallets.id then '1'
              else ''  end as transaction_type,deposite_transactions.created_at as date,
              deposite_transactions.amount as transaction_amount, deposite_transactions.status, wallets.name as wallet_name, deposite_transactions.amount,
              deposite_transactions.address_type
              from deposite_transactions
              join wallets on deposite_transactions.sender_wallet_id= wallets.id
                  or deposite_transactions.receiver_wallet_id = wallets.id
              where wallets.user_id=".Auth::user()->id."
                order by deposite_transactions.created_at desc");
//        dd($transactions);

        $y = [];
        if(isset($transactions[0])) {
            foreach ($transactions as $key=> $tran) {
                $y[date('d M y', strtotime($tran->date))][] = [
                    'wallet_name' => $tran->wallet_name,
                    'transaction_amount' => $tran->amount,
                    'address_type' => $tran->address_type == 'internal_address' ? addressType(ADDRESS_TYPE_EXTERNAL) : addressType($tran->address_type),
                    'transaction_type' => $tran->transaction_type,
                    'status_text' => deposit_status($tran->status),
                    'transaction_date' => date('d M y', strtotime($tran->date)),
                ];

            }
            $response = ['success' => true, 'activity_list' => $y, 'message' => __('Data get successfully')];
        } else {
            $response = ['success' => false, 'activity_list' =>(object)[], 'message' => __('Data not found')];
        }

        return $response;
    }

}
