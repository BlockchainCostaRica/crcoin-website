<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CreateWalletRequest;
use App\Http\Requests\Api\DepositRequest;
use App\Http\Requests\Api\WalletAddressRequest;
use App\Jobs\Withdrawal;
use App\Model\User\Wallet;
use App\Model\User\WalletAddressHistory;
use App\Repository\WalletRepository;
use App\Services\BitCoinApiService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    //my wallet list
    public function myWalletList()
    {
        $data = ['success'=> true, 'message' => __('Data get successfully')];
        $walletRepo = app(WalletRepository::class);
        $data['available_balance'] = $walletRepo->availableBalance(Auth::user()->id);
        $data['wallet_list'] = $walletRepo->walletList(Auth::user()->id)['wallet_list'];

        return response()->json($data);
    }

    // create wallet
    public function createWallet(CreateWalletRequest $request)
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $response = app(WalletRepository::class)->createNewWallet($request);
        if ($response) {
            $data = $response;
        }

        return $data;
    }

    // generate wallet address
    public function generateWalletAddress(WalletAddressRequest $request)
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $wallet = Wallet::where(['user_id' => Auth::user()->id, 'id'=> $request->wallet_id])->first();
        if (isset($wallet)) {
            $response = app(WalletRepository::class)->generateNewAddress($request->wallet_id);
            if ($response) {
                $data = $response;
            }
        } else {
            $data = ['success'=> false, 'message' => __('Wallet not found')];
        }

        return $data;
    }

    // wallet transaction history
    public function walletTransactionHistory($wallet_id)
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $walletRepo = app(WalletRepository::class);

        $response = $walletRepo->walletTransactionHistory($wallet_id);
        if ($response) {
            $data = $response;
        }
        $data['available_balance'] = $walletRepo->availableBalance(Auth::user()->id);
        $data['user_id'] = Auth::user()->id;

        return $data;
    }

    // all activity history
    public function allActivity()
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $walletRepo = app(WalletRepository::class);

        $response = $walletRepo->allActivityList();
        if ($response) {
            $data = $response;
        }
        $data['available_balance'] = $walletRepo->availableBalance(Auth::user()->id);

        return $data;
    }

    // deposit process
    public function withdrawalProcess(DepositRequest $request)
    {
        $data = ['success'=> false, 'message' => __('Invalid request')];
        $wallet = Wallet::where(['id' =>$request->wallet_id, 'user_id' => Auth::user()->id])->first();

        $NodeDetails = new \App\Services\wallet();
        $NodeDetails = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
        if (!$wallet) {
            $data['success'] = false;
            $data['message'] = __('Wallet not found!');
            return response()->json($data);
        }
        $address = $request->address;
        $user = $wallet->user;

//        if (empty($user->phone)) {
//            $data['success'] = false;
//            $data['has_phone'] = false;
//            $data['message'] = __("Please add your phone before send.");
//            return response()->json($data);
//        }
//
//        if ($user->phone_verified != STATUS_SUCCESS) {
//            $data['success'] = false;
//            $data['is_phone_verified'] = false;
//            $data['has_phone'] = true;
//            $data['message'] = __("Please verify your phone.");
//            return response()->json($data);
//        }
        if ($wallet->balance >= $request->amount) {
            if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {
                $receiverUser = User::where('email', $address)->first();
                if ( empty($receiverUser) ) {
                    return response()->json(['success'=>false,'message'=> __('Not a valid email address to send amount!')]);
                }
                if ( $user->id == $receiverUser->id ) {
                    return response()->json(['success'=>false,'message'=> __('You can\'t send to your own wallet!')]);
                }
            } else {
                $walletAddress = $this->isInternalAddress($address);
                if ($walletAddress) {
                    $receiverUser = $walletAddress->wallet->user;
                    if ( $user->id == $receiverUser->id ) {
                        return response()->json(['success'=>false,'message'=> __('You can\'t send to your own wallet!')]);
                    }
                }
                if ($NodeDetails->verifyAddress($address) == false) {
                    return response()->json(['success'=>false,'message'=>__('Address is not valid')]);
                }
            }
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $data['message'] = "Withdrawal message";
            $request = new Request();
            $request = $request->merge($data);

            dispatch(new Withdrawal($request->all()));

            return response()->json(['success'=>true,'message'=>__('Withdrawal placed successfully')]);
        } else {
            return response()->json(['success'=>false,'message'=>__('Wallet has no enough balance')]);
        }

    }


    //check internal address
    private function isInternalAddress($address)
    {
        return WalletAddressHistory::where('address', $address)->with('wallet')->first();
    }
}
