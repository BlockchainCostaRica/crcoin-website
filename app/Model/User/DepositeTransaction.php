<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;

class DepositeTransaction extends Model
{
   protected $fillable = ['user_id','sender_wallet_id','receiver_wallet_id','confirmations','status','address','address_type','type','amount','fees','transaction_id'];
    public function senderWallet(){
        return $this->belongsTo(Wallet::class,'sender_wallet_id','id');
    }
    public function receiverWallet(){
        return $this->belongsTo(Wallet::class,'receiver_wallet_id','id');
    }

}
