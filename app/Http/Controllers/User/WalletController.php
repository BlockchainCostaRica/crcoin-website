<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\withDrawRequest;
use App\Http\Services\TransactionService;
use App\Jobs\Withdrawal;
use App\Model\User\DepositeTransaction;
use App\Model\User\Wallet;
use App\Model\User\WalletAddressHistory;
use App\Model\User\WithdrawHistory;
use App\Services\BitCoinApiService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['wallets'] = Wallet::where('user_id',Auth::id())->get();
        $data['title'] = __('My Wallet');
        return view('User.my_wallet.index',$data);

    }

    // make default account
    public function makeDefaultAccount($account_id)
    {
        Wallet::where('user_id',Auth::id())->update(['is_primary'=>0]);
        Wallet::updateOrCreate(['id'=>$account_id],['is_primary'=>1]);

        return redirect()->back()->with('success',__('Default set successfully'));
    }

    // create new wallet
    public function createWallet(Request $request)
    {
        if (!empty($request->wallet_name)) {
            if ($request->ajax()) {
                return response()->json(['success'=>true]);
            } else {
                $wallet = new Wallet();
                $wallet->user_id = Auth::id();
                $wallet->name = $request->wallet_name;
                $wallet->status = STATUS_SUCCESS;
                $wallet->balance = 0;
                $wallet->save();

                return redirect()->back()->with('success',__("Wallet create successfully"));
            }
        }
        return response()->json(['success'=>false,'message'=>__("Wallet name can't be empty")]);
    }

    // generate new wallet address
    public function generateNewAddress(Request $request)
    {
        try {
            $wallet = new \App\Services\wallet();
//            $api = new BitCoinApiService('root','pass','140.82.48.82','8333');
            $api = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
            $address = $api->getNewAddress();
            $wallet->AddWalletAddressHistory($request->wallet_id,$address);

            return redirect()->back()->with(['success'=>__('Address generated successfully')]);

        } catch (\Exception $e) {
            return redirect()->back()->with('dismiss', $e->getMessage());
        }

    }

    // generate qr code
    public function qrCodeGenerate(Request $request)
    {
        $image = QRCode::text($request->address)->png();
        return response($image)->header('Content-type','image/png');
    }

    // wallet details
    public function walletDetails(Request $request,$id)
    {
        $data['address'] =  new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));
        $exists = WalletAddressHistory::where('wallet_id',$id)->orderBy('created_at','desc')->first();
        $data['address'] = (!empty($exists)) ? $exists->address : $data['address']->getNewAddress();
        $data['wallet_id'] = $id;
        $data['wallet'] = Wallet::find($id);
        $data['address_histories'] = WalletAddressHistory::where('wallet_id',$id)->get();
        $data['histories'] = DepositeTransaction::where('receiver_wallet_id',$id)->get();
        $data['withdraws'] = WithdrawHistory::where('wallet_id',$id)->get();
        $data['active'] = $request->q;
        $data['title'] = $request->q;

        if (empty($exists)) {
            $history = new \App\Services\wallet();
            $history->AddWalletAddressHistory($id,  $data['address']);
        }
        return view('User.my_wallet.wallet_details',$data);
    }

    // withdraw balance
    public function WithdrawBalance(withDrawRequest $request) {
        $wallet = Wallet::find($request->wallet_id);
        $NodeDetails = new \App\Services\wallet();
        $NodeDetails = new BitCoinApiService(settings('coin_api_user'),settings('coin_api_pass'),settings('coin_api_host'),settings('coin_api_port'));

        $address = $request->address;
        $user = $wallet->user;
        if ($request->ajax()) {
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
                }
                if ($NodeDetails->verifyAddress($address)) {
                    return response()->json(['success'=>true]);

                } else {
                    return response()->json(['success'=>false,'message'=>__('Address is not valid222')]);
                }
            } else {
                return response()->json(['success'=>false,'message'=>__('Wallet has no enough balance')]);
            }

        } else {
            if ( filter_var($address, FILTER_VALIDATE_EMAIL) ) {

                $receiverUser = User::where('email', $address)->first();

                if ( empty($receiverUser) ) {
                    return redirect()->back()->with('dismiss', __('Not a valid email address to send amount!'));
                }
                if ( $user->id == $receiverUser->id ) {
                    return redirect()->back()->with('dismiss', __('You can\'t send to your own wallet!'));
                }

            } else {
                $walletAddress = $this->isInternalAddress($address);
                if ($walletAddress) {
                    $receiverUser = $walletAddress->wallet->user;
                    if ( $user->id == $receiverUser->id ) {
                        return redirect()->back()->with('dismiss', __('You can\'t send to your own wallet!'));
                    }
                }
            }
            $user = Auth::user();
            $google2fa = new Google2FA();
            if(empty($request->code)) {
                return redirect()->back()->with('dismiss',__('Verify code is required'));
            }
            $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code);

            $data = $request->all();
            $data['user_id'] = Auth::id();
            $request = new Request();
            $request = $request->merge($data);

            if ($valid) {
                if ($wallet->balance >= $request->amount) {
//                    $request =$request->all();
//
//                    $trans = new TransactionService();
//                    $response = $trans->send($request['wallet_id'],$request['address'],$request['amount'],'','',$request['user_id'],$request['message']);

                  dispatch(new Withdrawal($request->all()));
                    return redirect()->back()->with('success',__('Withdrawal placed successfully'));
                } else
                    return redirect()->back()->with('dismiss',__('Wallet has no enough balance'));
            } else
                return redirect()->back()->with('dismiss',__('Google two factor authentication is invalid'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //check internal address
    private function isInternalAddress($address)
    {
        return WalletAddressHistory::where('address', $address)->with('wallet')->first();
    }
}
