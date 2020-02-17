<?php

namespace App\Http\Controllers\User;

use App\Http\Services\CommonService;
use App\Model\User\DepositeTransaction;
use App\Model\User\WithdrawHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeshboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = __('Dashboard');
        $from = Carbon::now()->subMonth(6)->format('Y-m-d h:i:s');
        $to = Carbon::now()->format('Y-m-d h:i:s');

        $common_service = new CommonService();

        if (!$request->ajax()){
            $sixmonth_diposites = $common_service->AuthUserDiposit($from,$to);
            $sixmonth_withdraws = $common_service->AuthUserWithdraw($from,$to);

            ///////////////////////////////////////////   six month data /////////////////////////////
            $data['sixmonth_diposites'] = [];
            $months = previousMonthName(5);

            foreach ($months as $key => $month){
                $data['sixmonth_diposites'][$key]['country'] = $month;
                $data['sixmonth_diposites'][$key]['year2004'] = (isset($sixmonth_diposites[$month])) ? $sixmonth_diposites[$month] : 0;
                $data['sixmonth_diposites'][$key]['year2005'] = (isset($sixmonth_withdraws[$month])) ? $sixmonth_withdraws[$month] : 0;
            }
        }
        /////////////////////////////////////////// end  six month data /////////////////////////////

        $data['completed_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_SUCCESS)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');
        $data['pending_withdraw']  = WithdrawHistory::join('wallets','wallets.id','withdraw_histories.wallet_id')
            ->where('withdraw_histories.status',STATUS_PENDING)
            ->where('wallets.user_id',Auth::id())->sum('withdraw_histories.amount');


        if ($request->ajax()){
            if ($request->t == 'year-tab'){
                /////////////////////////////////////////// year data /////////////////////////////
                $from = Carbon::now()->startOfYear()->format('Y-m-d h:i:s');
                $to = Carbon::now()->endOfYear()->format('Y-m-d h:i:s');
                $this_year_diposites = $common_service->AuthUserDiposit($from,$to);
                $this_year_withdraws = $common_service->AuthUserWithdraw($from,$to);

                $data['label'] = previousYearMonthName();
                $data['Deposite'] = [];
                $data['Withdraw'] = [];
                $months = previousYearMonthName();
                foreach ($months as $key => $month) {
                    array_push($data['Deposite'],(isset($this_year_diposites[$month])) ? $this_year_diposites[$month] : 0);
                    array_push($data['Withdraw'],(isset($this_year_withdraws[$month])) ? $this_year_withdraws[$month] : 0);
                }
                /////////////////////////////////////////// end year data /////////////////////////////
            } elseif ($request->t == 'week-tab') {
                /////////////////////////////////////////// week data /////////////////////////////
                $from = Carbon::now()->startOfWeek()->format('Y-m-d');
                $to = Carbon::now()->endOfWeek()->format('Y-m-d');
                $this_week_diposites = $common_service->AuthUserDiposit($from,$to,'week');
                $this_week_withdraws = $common_service->AuthUserWithdraw($from,$to,'week');
                $data['label'] = previousDayName();
                $data['Deposite'] = [];
                $data['Withdraw'] = [];
                $months = previousDayName();

                foreach ($months as $key => $month){
                    array_push($data['Deposite'],(isset($this_week_diposites[$month])) ? $this_week_diposites[$month] : '');
                    array_push($data['Withdraw'],(isset($this_week_withdraws[$month])) ? $this_week_withdraws[$month] : '');
                }

                /////////////////////////////////////////// end week data /////////////////////////////
            } elseif ($request->t == 'month-tab'){
                /////////////////////////////////////////// this month data /////////////////////////////
                $from = Carbon::now()->startOfMonth()->format('Y-m-d h:i:s');
                $to = Carbon::now()->endOfMonth()->format('Y-m-d h:i:s');
                $this_month_diposites = $common_service->AuthUserDiposit($from,$to,'month');
                $this_month_withdraws = $common_service->AuthUserWithdraw($from,$to,'month');
                $data['label'] = previousMonthDateName();
                $data['Deposite'] = [];
                $data['Withdraw'] = [];
                $months = previousMonthDateName();
                foreach ($months as $key => $month) {
                    array_push($data['Deposite'],(isset($this_month_diposites[$month])) ? $this_month_diposites[$month] : 0);
                    array_push($data['Withdraw'],(isset($this_month_withdraws[$month])) ? $this_month_withdraws[$month] : 0);
                }
                /////////////////////////////////////////// this month data /////////////////////////////
            }

            return response()->json($data);
        }
        $data['histories'] = DepositeTransaction::get();
        $data['withdraws'] = WithdrawHistory::get();

      return view('User.dashboard.index',$data);

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
}
