<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\CategoryManage;
use App\Models\FeatureImage;

use App\Models\ListIp;
use App\Models\SiteManage;
use App\Models\User;

use App\Models\Wallpapers;
use Carbon\Carbon;

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


    public function directlink(){
        $domain=$_SERVER['SERVER_NAME'];
        $site = SiteManage::where('site_name',$domain)->first();
        if($site){
            $directlink = $site->directlink;
            if ($directlink){
                $site->view_page = $site->view_page+1;
                $site->save();
                return redirect($directlink);
            }else{
                return redirect('/');
            }
        }
        else{
            return view('content.hp');
        }
    }

    public function wallpapers(){
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = Wallpapers::whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',1)
                    ->select('tbl_category_manages.*');
            })->inRandomOrder()->paginate(12);
        } else{
            $data = Wallpapers::whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',0)
                    ->select('tbl_category_manages.*');
            })->inRandomOrder()->paginate(12);
        }
        $pageConfigs = [
            'pageClass' => 'ecommerce-application',
        ];

        return view('content.show', [
            'pageConfigs' => $pageConfigs,
            'data' => $data
        ]);

        return view('content.show',compact('data'));

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
        $categories = CategoryManage::all();
        $wallpaper = Wallpapers::all();
        $sites = SiteManage::all();
        $topViews = $this->topView();


        return view('content.home')->with(compact(
            'categories',
            'wallpaper',
            'sites',
            'topViews'

        ));
    }

    public function file()
    {
        return view('content.file.index');
    }

    public function getListIps(){
        $data = ListIp::whereDate('created_at', Carbon::today())->get();
        return $data;
    }

    public function topView(){
        $date =request()->time;
//        dd(request()->time);
//        $date =
        $now = Carbon::now();
        $sites = SiteManage::all();
        $data_arr = array();
        foreach ($sites as $site){
            if($date == 'inMonth' ){
                $data_arr[] = array(
                    'logo' => $site->header_image,
                    'site_name' => $site->site_name,
                    'name_site' => $site->name_site,
                    'count' => ListIp::where('id_site',$site->id)->whereBetween('created_at', [
                        $now->startOfMonth()->format('Y-m-d'), //This will return date in format like this: 2022-01-10
                        $now->endOfMonth()->format('Y-m-d')
                    ])->count()
                );
            }elseif ($date == 'inWeek'){
                $data_arr[] = array(
                    'logo' => $site->header_image,
                    'site_name' => $site->site_name,
                    'name_site' => $site->name_site,
                    'count' => ListIp::where('id_site',$site->id)->whereBetween('created_at', [
                        $now->startOfWeek()->format('Y-m-d'), //This will return date in format like this: 2022-01-10
                        $now->endOfWeek()->format('Y-m-d')
                    ])->count()
                );
            }else{
                $data_arr[] = array(
                    'logo' => $site->header_image,
                    'site_name' => $site->site_name,
                    'name_site' => $site->name_site,
                    'count' => ListIp::where('id_site',$site->id)->whereDate('created_at', Carbon::today())->count()
                );
            }
        }
        usort($data_arr, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        $data_arr = array_slice($data_arr, 0, 5);
        return $data_arr;
    }

}
