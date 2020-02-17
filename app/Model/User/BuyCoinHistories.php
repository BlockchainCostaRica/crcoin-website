<?php

namespace App\Model\User;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BuyCoinHistories extends Model
{
   protected $fillable = ['confirmations','status'];

    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }


}
