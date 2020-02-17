<?php
/**
 * Created by PhpStorm.
 * User: jony
 * Date: 8/5/19
 * Time: 1:58 PM
 */

namespace App\Services;

use App\Model\User\WalletAddressHistory;

class wallet
{
    public function AddWalletAddressHistory($wallet_id, $address)
    {
        $wallet = new WalletAddressHistory();
        $wallet->wallet_id = $wallet_id;
        $wallet->address = $address;
        $wallet->save();
        return ['success'=>true];
    }
}
