<?php

namespace App\Http\Controllers\User;

use App\Model\Admin\Bank;
use App\Http\Requests\btcDepositeRequest;
use App\Http\Services\CommonService;
use App\Model\User\Bonus;
use App\Model\User\BuyCoinHistories;
use App\Model\User\Wallet;
use App\Services\BitCoinApiService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;


class CoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = __('Buy Coin');
        $settings = allsetting();
        $data['address'] = isset($settings['admin_coin_address']) ? $settings['admin_coin_address'] : 'invalid address';
        $data['banks'] = Bank::where(['status' => STATUS_ACTIVE])->get();

        return view('User.buy_coin.index',$data);
    }


    // buy coin process
    public function buyCoin(btcDepositeRequest $request)
    {
        $url = file_get_contents('https://min-api.cryptocompare.com/data/price?fsym=USD&tsyms=BTC');

        if (isset(\GuzzleHttp\json_decode($url, true)['BTC'])) {
            $coin_price_doller = bcmul($request->coin, settings('coin_price'));
            $coin_price_btc = bcmul(\GuzzleHttp\json_decode($url, true)['BTC'], $coin_price_doller);
            $coin_price_btc = number_format($coin_price_btc, 8);

            if ($request->payment_type == BTC) {
                $btc_transaction = new BuyCoinHistories();
                $btc_transaction->address = $request->btc_address;
                $btc_transaction->type = BTC;
                $btc_transaction->user_id = Auth::id();
                $btc_transaction->coin = $request->coin;
                $btc_transaction->doller = $coin_price_doller;
                $btc_transaction->btc = $coin_price_btc;
                $btc_transaction->save();

                return redirect()->back()->with('success', "Request submitted successful,please send BTC with this address");
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
                        return redirect()->back()->with('success', "Coin purchased successfully ");

                        // all good
                    } catch (\Exception $e) {

                        DB::rollback();
                        // something went wrong
                        return redirect()->back()->with('dismiss', __("Something went wrong"));
                    }
                } else {
                    return redirect()->back()->with('dismiss', $trans['message']);
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

                return redirect()->back()->with('success', "Request submitted successful,Please wait for admin approval");
            }
        } else {
            return redirect()->back()->with('dismiss', "Somwthing went wrong");
        }
    }

    //
    public function referralBonus(Request $request){
        if ($request->ajax()) {
            $query = Bonus::where('user_id',Auth::id())->where('commission','!=',0);

            return datatables()->of($query)
                ->addColumn('commission', function ($item) {
                    return $item->commission;
                })->rawColumns(['actions'])
                ->make(true);
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

    //bank details
    public function bankDetails(Request $request)
    {
        $data = ['success' => false, 'message' => __('Invalid request'), 'data_genetare'=> ''];
        $data_genetare = '';
        if(isset($request->val)) {
            $bank = Bank::where('id', $request->val)->first();
            if (isset($bank)) {
                $data_genetare = '<h3 class="text-center">'.__('Bank Details').'</h3><table class="table">';
                $data_genetare .= '<tr><td>'.__("Bank Name").' :</td> <td>'.$bank->bank_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Account Holder Name").' :</td> <td>'.$bank->account_holder_name.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Bank Address").' :</td> <td>'.$bank->bank_address.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Country").' :</td> <td>'.country($bank->country).'</td></tr>';
                $data_genetare .= '<tr><td>'.__("IBAN").' :</td> <td>'.$bank->iban.'</td></tr>';
                $data_genetare .= '<tr><td>'.__("Swift Code").' :</td> <td>'.$bank->swift_code.'</td></tr>';
                $data_genetare .= '</table>';
                $data['data_genetare'] = $data_genetare;
                $data['success'] = true;
                $data['message'] = __('Data get successfully.');
            }
        }

        return response()->json($data);
    }
}
