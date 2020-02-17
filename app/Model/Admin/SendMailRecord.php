<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class SendMailRecord extends Model
{
    protected $fillable = [
        'user_id', 'status', 'email_type'
    ];
}
