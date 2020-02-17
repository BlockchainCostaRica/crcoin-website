<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    protected $fillable = ['slug', 'value'];
}
