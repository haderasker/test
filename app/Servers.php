<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;

class Servers extends Model
{


    protected $table = 'servers';

    protected $fillable = [
        'name',
        'serverUrl'
    ];


    public static function boot()
    {
        parent::boot();

        Servers::observe(new UserActionsObserver);
    }


}