<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OmerSalaj\MobileAppVersion\AndroidMobileAppVersion;

class FileController extends Controller
{


    public function index(){

//        dd(Auth::id());
        return view('/content/file/index');
    }
    public function upload(Request $request){
        dd(1);
    }

}
