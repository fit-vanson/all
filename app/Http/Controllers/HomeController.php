<?php

namespace App\Http\Controllers;

use App\Models\FeatureImage;

use App\Models\SiteManage;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use ImageOrientationFix\ImageOrientationFixer;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Monolog\Logger;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    private $user;
    public $role;
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }
    public function show(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = SiteManage::where('site_name',$domain)->first();
        if($site){
            $images=FeatureImage::where('site_id',$site->id)->get();
            return view('content.index')->with(compact('images','site'));
        }
        else{
            return view('content.hp');
        }
    }
    public function policy(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = SiteManage::where('site_name',$domain)->first();
        if($site){
            return view('content.policy')->with(compact('site'));
        }
        else{
            return view('content.hp');
        }
    }

    public function home()
    {
        return view('content.home');
    }

    public function file()
    {
        return view('content.file.index');
    }

}
