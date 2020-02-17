<?php

namespace App\Model\User;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id','name','balance','referral_balance','status','is_primary'];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }
}
