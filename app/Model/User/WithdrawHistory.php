<?php

namespace App\Model\User;

use App\User;
use Illuminate\Database\Eloquent\Model;

class WithdrawHistory extends Model
{
    protected $fillable = ['receiver_wallet_id','user_id','wallet_id','confirmations','status','address','address_type','amount','fees','transaction_hash','message'];
    public function senderWallet(){
        return $this->belongsTo(Wallet::class,'sender_wallet_id','id');
    }
    public function receiverWallet(){
        return $this->belongsTo(Wallet::class,'receiver_wallet_id','id');
    }
    public function wallet()
    {
        return $this->belongsTo(Wallet::class,'wallet_id');
    }
    public function users(){
        return $this->belongsTo(User::class,'wallet_id');
    }
}
