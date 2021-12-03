<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Auth;

class LfmConfigHandler extends \UniSharp\LaravelFilemanager\Handlers\ConfigHandler
{
    public function userField()
    {
//        return auth()->user()->name;
//        dd(Auth::user()->hasRole());

        if(Auth::user()->hasRole('Admin')){
            return ;
        }else{
            return auth()->user()->name;
        }
    }
}
