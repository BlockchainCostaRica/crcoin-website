<?php

namespace App\Http\Controllers\Admin;

use App\Model\User\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function adminWallets(Request $request){
        if($request->ajax()){
            $data['wallets'] = Wallet::join('users','users.id','=','wallets.user_id')
                ->select(
                    'wallets.name'
                    ,'wallets.balance'
                    ,'wallets.created_at'
                    ,'users.first_name'
                    ,'users.last_name'
                    ,'users.email'
                );

            return datatables()->of($data['wallets'])
                ->addColumn('user_name',function ($item){return $item->first_name.' '.$item->last_name;})
                ->make(true);
        }

        return view('Admin.wallets.index');
    }

}
