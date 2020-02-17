<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliationHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'amount', 'system_fees', 'transaction_id', 'child_id', 'level', 'status', 'order_type' ];

    protected $dates = ['deleted_at'];
}
