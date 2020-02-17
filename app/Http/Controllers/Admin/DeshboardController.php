<?php

namespace App\Http\Controllers\Admin;

use App\Http\Services\CommonService;
use App\Model\User\BuyCoinHistories;
use App\Model\User\DepositeTransaction;
use App\Model\User\WithdrawHistory;
use App\User;
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
         $data['users'] = User::count();
         $data['active_users'] = User::where('status',STATUS_SUCCESS)->count();
         $data['deleted_users'] = User::where('status',STATUS_DELETED)->count();
         $data['suspended_users'] = User::where('status',STATUS_SUSPENDED)->count();
         $data['total_coin'] = BuyCoinHistories::where('status', STATUS_SUCCESS)->sum('coin');
         $data['total_doller'] = BuyCoinHistories::where('status', STATUS_SUCCESS)->sum('doller');

         $common_service = new CommonService();
        $monthlyUsers = User::select(DB::raw('count(DISTINCT id) as totalUser'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where('type', USER_ROLE_USER)
            ->groupBy('months')
            ->get();

        $allMonths = all_months();
        if (isset($monthlyUsers[0])) {
            foreach ($monthlyUsers as $usr) {
                $data['user'][$usr->months] = $usr->totalUser;
            }
        }
        $allUsers= [];
        foreach ($allMonths as $month) {
            $allUsers[] =  isset($data['user'][$month]) ? (int)$data['user'][$month] : 0;
        }
        $data['monthly_user'] = $allUsers;

        // deposit
        $monthlyDeposits = DepositeTransaction::select(DB::raw('sum(amount) as totalDepo'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyDeposits[0])) {
            foreach ($monthlyDeposits as $depsit) {
                $data['deposit'][$depsit->months] = $depsit->totalDepo;
            }
        }
        $allDeposits = [];
        foreach ($allMonths as $month) {
            $allDeposits[] =  isset($data['deposit'][$month]) ? $data['deposit'][$month] : 0;
        }
        $data['monthly_deposit'] = $allDeposits;

        // withdrawal
        $monthlyWithdrawals = WithdrawHistory::select(DB::raw('sum(amount) as totalWithdraw'), DB::raw('MONTH(created_at) as months'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status', STATUS_SUCCESS)
            ->groupBy('months')
            ->get();

        if (isset($monthlyWithdrawals[0])) {
            foreach ($monthlyWithdrawals as $withdraw) {
                $data['withdrawal'][$withdraw->months] = $withdraw->totalWithdraw;
            }
        }
        $allWithdrawal = [];
        foreach ($allMonths as $month) {
            $allWithdrawal[] =  isset($data['withdrawal'][$month]) ? $data['withdrawal'][$month] : 0;
        }
        $data['monthly_withdrawal'] = $allWithdrawal;
        
         return view('Admin.dashboard.index',$data);
    }

    // admin profile
    public function adminProfile(Request $request)
    {
//        $data['qr'] = (!empty($request->qr)) ? $request->qr : 'profile-tab';
        $data['tab']='profile';
        if(isset($_GET['tab'])){
            $data['tab']=$_GET['tab'];
        }
        if ($request->ajax()) {
            $data['items'] = [];
            return datatables()->of($data['items'])
                ->addColumn('is_default', function ($c) {
                    return ($c->is_default == 1) ? __('Yes') : 'No';
                })
                ->addColumn('created_at', function ($c) {
                    return !empty($c->created_at)?$c->created_at:'N/A';
                })
                ->make(true);

        }
        $data['base_coins'] = [];
        $data['settings'] = allsetting();

        return view('Admin.my_profile.index',$data);
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
