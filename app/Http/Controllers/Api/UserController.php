<?php

namespace App\Http\Controllers\Api;

use App\Model\Admin\Bank;
use App\Repository\WalletRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //bank list
    public function bankList()
    {
        $data = ['success' => true, 'bank_list' => [], 'message' => __('Data get successfully')];
        $data['available_balance'] = app(WalletRepository::class)->availableBalance(Auth::user()->id);
        $banks = Bank::where('status', STATUS_ACTIVE)->get();
        if (isset($banks[0])) {
            $data['bank_list'] = $banks;
        }

        return $data;
    }

    // bank details
    public function bankDetails($id)
    {
        $data = ['success' => false, 'bank' => (object)[], 'message' => __('Data not found')];
        $data['available_balance'] = app(WalletRepository::class)->availableBalance(Auth::user()->id);
        $bank = Bank::where(['status'=>STATUS_ACTIVE, 'id'=> $id])->first();
        if (isset($bank)) {
            $data['success'] = true;
            $data['message'] = __('Data get successfully');
            $data['bank'] = $bank;
        }

        return $data;
    }
}
