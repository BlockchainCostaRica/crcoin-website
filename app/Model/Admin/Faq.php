<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = [
       'question'
        ,'answer'
        ,'priority'
    ];
}
