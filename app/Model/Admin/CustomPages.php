<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class CustomPages extends Model
{
    protected $fillable = [
        'title','key','description','status'
    ];
}
