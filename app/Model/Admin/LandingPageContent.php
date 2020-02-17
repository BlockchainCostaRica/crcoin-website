<?php

namespace App\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class LandingPageContent extends Model
{
    protected $fillable = [
        'title'
        ,'description'
        ,'image'
        ,'priority'
        ,'status'
    ];
}
