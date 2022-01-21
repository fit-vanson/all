<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\FeatureWallpaperResource;
use App\Http\Resources\WallpaperResource;
use App\Models\CategoryManage;
use App\Models\ListIp;
use App\Models\SiteManage;
use App\Models\Visitor;
use App\Models\VisitorFavorite;
use App\Http\Controllers\Controller;
use App\Models\Wallpapers;
use Carbon\Carbon;

class WallpaperController extends Controller
{
    public function show($id,$device_id)
    {

        $wallpaper = Wallpapers::findOrFail($id);
        $wallpaper->increment('view_count');
        $visitorFavorite = VisitorFavorite::where([
            'wallpaper_id' => $id,
            'visitor_id' => Visitor::where('device_id', $device_id)->value('id')])->first();
        if($visitorFavorite){
            return response()->json([
                'categories' =>
                    CategoryResource::collection($wallpaper->category),
                'id' => $wallpaper->id,
                'name' => $wallpaper->name,
                'thumbnail_image' => asset('storage/wallpapers/thumbnail/'. $wallpaper->thumbnail_image),
                'detail_image' => asset('storage/wallpapers/detail/' . $wallpaper->image),
                'download_image' => asset('storage/wallpapers/download/' . $wallpaper->origin_image),
                'liked' => 1,
                'like_count' => $wallpaper->like_count,
                'views' => $wallpaper->view_count,
                'feature' => $wallpaper->feature,
                'created_at' => $wallpaper->created_at->format('d/m/Y'),
            ]);
        }else{
            return response()->json([
                'categories' =>
                    CategoryResource::collection($wallpaper->category),
                'id' => $wallpaper->id,
                'name' => $wallpaper->name,
                'thumbnail_image' => asset('storage/wallpapers/thumbnail/'.$wallpaper->thumbnail_image),
                'detail_image' => asset('storage/wallpapers/detail/' . $wallpaper->image),
                'download_image' => asset('storage/wallpapers/download/' . $wallpaper->origin_image),
                'liked' => 0,
                'like_count' => $wallpaper->like_count,
                'views' => $wallpaper->view_count,
                'feature' => $wallpaper->feature,
                'created_at' => $wallpaper->created_at->format('d/m/Y'),
            ]);
        }
    }

    public function getFeatured()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress= $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ipaddress = 'UNKNOWN';

        $site=SiteManage::where('site_name',$domain)->first();
        $listIp=ListIp::where('ip_address',$ipaddress)->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
        if(!$listIp){
            ListIp::create([
                'ip_address'=>$ipaddress,
                'id_site' => $site->id
            ]);
        }else{
            $listIp=ListIp::where('ip_address',get_ip())->where('id_site',$site->id)->whereDate('created_at', Carbon::today())->first();
            if(!$listIp){
                ListIp::create([
                    'ip_address'=>get_ip(),
                    'id_site' => $site->id
                ]);
            }
        }
        $load_feature=$site->load_view_by;
        if (checkBlockIp()) {
            if($load_feature ==0){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',1)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->inRandomOrder()
                    ->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature == 1){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',1)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->orderBy('order', 'desc')->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature == 2){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',1)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->orderBy('view_count', 'desc')->get();

                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature == 3){
                $data = Wallpapers::where('feature', 1)->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',1)
                        ->select('tbl_category_manages.*');
                })->inRandomOrder()->take(12)->get();

                $getResource = WallpaperResource::collection($data);
            }
        } else {
            if($load_feature ==0){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',0)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->inRandomOrder()->get();
                $getResource= FeatureWallpaperResource::collection($data);
//                dd($getResource);
            }elseif($load_feature ==1){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',0)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->orderBy('order', 'desc')->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==2){
                $data = CategoryManage::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name',$domain)
                    ->where('tbl_category_manages.checked_ip',0)
                    ->select('tbl_category_manages.*')
                    ->has('wallpaper')
                    ->with(['wallpaper'=>function ($q) {
                        $q->latest();
                    }])
                    ->orderBy('view_count', 'desc')->get();
                $getResource= FeatureWallpaperResource::collection($data);
            }elseif($load_feature ==3){
                $data = Wallpapers::where('feature', 1)->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('tbl_category_manages.checked_ip',0)
                        ->select('tbl_category_manages.*');
                })->inRandomOrder()->take(12)->get();
                $getResource = WallpaperResource::collection($data);
            }
        }
        return response()->json([
            'message'=>'save ip successs',
            'ad_switch'=>$site->ad_switch,
            'data'=>$getResource,
        ]);

    }
    public function getPopulared()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()){

            $data = Wallpapers::where('like_count','>=',1)
                ->orderBy('like_count','desc')
                ->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('checked_ip','=', 1);
                })
                ->paginate(70);
        }else{
            $data = Wallpapers::where('like_count','>=',1)
                ->orderBy('like_count','desc')
                ->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('checked_ip','=', 0);
                })
                ->paginate(70);
        }
        return WallpaperResource::collection($data);
    }
    public function getNewest()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()){
            $data = Wallpapers::orderBy('created_at','desc')
                ->where('like_count','>=',1)
                ->orderBy('like_count','desc')
                ->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('checked_ip','=', 1);
                })
                ->paginate(70);


//            $data = Wallpapers::orderBy('created_at','desc')
//                ->whereHas('category', function ($q)
//                {
//                    $q->where('checked_ip','=', 1);
//                })
//                ->paginate(70);
        }else {

            $data = Wallpapers::orderBy('created_at','desc')
                ->where('like_count','>=',1)
                ->orderBy('like_count','desc')
                ->whereHas('category', function ($q) use ($domain) {
                    $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                        ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                        ->where('site_name',$domain)
                        ->where('checked_ip','=', 0);
                })
                ->paginate(70);
//            $data = Wallpapers::orderBy('created_at','desc')
//                ->whereHas('category', function ($q)
//                {
//                    $q->where('checked_ip','=', 0);
//                })
//                ->paginate(70);
        }
        return WallpaperResource::collection($data);
    }
}
