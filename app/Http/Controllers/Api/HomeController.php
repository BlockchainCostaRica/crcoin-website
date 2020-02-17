<?php

namespace App\Http\Controllers\Api;

use App\Http\Services\CommonService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //home data
    public function home(Request $request)
    {
        try {
            $data = ['success' => true, 'message' => __('Data get successfully')];
            $from = Carbon::now()->subMonth(6)->format('Y-m-d h:i:s');
            $to = Carbon::now()->format('Y-m-d h:i:s');
            $common_service = new CommonService();
            $sixmonth_diposites = $common_service->AuthUserDiposit($from,$to);
            $sixmonth_withdraws = $common_service->AuthUserWithdraw($from,$to);

            ///////////////////////////////////////////   six month data /////////////////////////////
            $data['sixmonth_diposite_withdrawal'] = [];
            $months = previousMonthName(5);

            foreach ($months as $key => $month){
                $data['sixmonth_diposite_withdrawal'][$key]['month'] = $month;
                $data['sixmonth_diposite_withdrawal'][$key]['six_deposit'] = (isset($sixmonth_diposites[$month])) ? $sixmonth_diposites[$month] : "0";
                $data['sixmonth_diposite_withdrawal'][$key]['six_withdrawal'] = (isset($sixmonth_withdraws[$month])) ? $sixmonth_withdraws[$month] : "0";
            }

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
                array_push($data['Deposite'],(isset($this_week_diposites[$month])) ? $this_week_diposites[$month] : "0");
                array_push($data['Withdraw'],(isset($this_week_withdraws[$month])) ? $this_week_withdraws[$month] : "0");
            }

            if ($request->time_period == 'year'){
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
                    array_push($data['Deposite'],(isset($this_year_diposites[$month])) ? $this_year_diposites[$month] : "0");
                    array_push($data['Withdraw'],(isset($this_year_withdraws[$month])) ? $this_year_withdraws[$month] : "0");
                }
                /////////////////////////////////////////// end year data /////////////////////////////
            } elseif ($request->time_period == 'week') {
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
                    array_push($data['Deposite'],(isset($this_week_diposites[$month])) ? $this_week_diposites[$month] : "0");
                    array_push($data['Withdraw'],(isset($this_week_withdraws[$month])) ? $this_week_withdraws[$month] : "0");
                }

                /////////////////////////////////////////// end week data /////////////////////////////
            } elseif ($request->time_period == 'month'){
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
                    array_push($data['Deposite'],(isset($this_month_diposites[$month])) ? $this_month_diposites[$month] : "0");
                    array_push($data['Withdraw'],(isset($this_month_withdraws[$month])) ? $this_month_withdraws[$month] : "0");
                }
                /////////////////////////////////////////// this month data /////////////////////////////
            }


        } catch(\Exception $e) {
            $data = ['success' => false, 'sixmonth_diposite_withdrawal' => [], 'message' => __('Invalid request')];
        }

        return response()->json($data);
    }
}
