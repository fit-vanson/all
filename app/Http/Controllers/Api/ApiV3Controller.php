<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResource_V3;
use App\Models\CategoryManage;
use App\Models\ListIp;
use App\Models\SiteManage;
use App\Models\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiV3Controller extends Controller
{
    public function index(){
        dd(1);
    }
    public function checkCode(Request $request,$version, $token, $item_code){


        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
            $ipaddress = $_SERVER["HTTP_CF_CONNECTING_IP"];
        else
            $ipaddress = 'UNKNOWN';
        $domain = $_SERVER['SERVER_NAME'];
        $site = SiteManage::where('site_name', $domain)->first();
        $listIp = ListIp::where('ip_address', $ipaddress)->where('id_site', $site->id)->whereDate('created_at', Carbon::today())->first();
        if (!$listIp) {
            ListIp::create([
                'ip_address' => $ipaddress,
                'id_site' => $site->id
            ]);
        } else {
            $listIp = ListIp::where('ip_address', get_ip())->where('id_site', $site->id)->whereDate('created_at', Carbon::today())->first();
            if (!$listIp) {
                ListIp::create([
                    'ip_address' => get_ip(),
                    'id_site' => $site->id
                ]);
            }
        }


        $ads = json_decode($site->ads, true);

        $response_publisher_id["name"] = "ADMIN_PUBLISHER_ID";
        $response_publisher_id["value"] = $ads ? $ads['AdMob_Publisher_ID'] : '';

        $response_app_id["name"] = "ADMIN_APP_ID";
        $response_app_id["value"] =$ads ?  $ads['AdMob_App_ID'] : '';

        $response_ads_rewarded["name"] = "ADMIN_REWARDED_ADMOB_ID";
        $response_ads_rewarded["value"] = '';



        $response_ads_interstitial_admob_id["name"] = "ADMIN_INTERSTITIAL_ADMOB_ID";
        $response_ads_interstitial_admob_id["value"] = $ads ?  $ads['AdMob_Interstitial_Ad_Unit_ID']: '';

        $response_ads_interstitial_facebook_id["name"] = "ADMIN_INTERSTITIAL_FACEBOOK_ID";
        $response_ads_interstitial_facebook_id["value"] = '';


        $response_ads_interstitial_type["name"] = "ADMIN_INTERSTITIAL_TYPE";
        $response_ads_interstitial_type["value"] = 'BOTH';

        $response_ads_interstitial_click["name"] = "ADMIN_INTERSTITIAL_CLICKS";
        $response_ads_interstitial_click["value"] = 3;

        $response_ads_banner_admob_id["name"] = "ADMIN_BANNER_ADMOB_ID";
        $response_ads_banner_admob_id["value"] =$ads ?  $ads['AdMob_Banner_Ad_Unit_ID']: '';


        $response_ads_banner_facebook_id["name"] = "ADMIN_BANNER_FACEBOOK_ID";
        $response_ads_banner_facebook_id["value"] = "";

        $response_ads_banner_type["name"] = "ADMIN_BANNER_TYPE";
        $response_ads_banner_type["value"] = "BOTH";

        $response_ads_native_facebook_id["name"] = "ADMIN_NATIVE_FACEBOOK_ID";
        $response_ads_native_facebook_id["value"] = "";

        $response_ads_native_admob_id["name"] = "ADMIN_NATIVE_ADMOB_ID";
        $response_ads_native_admob_id["value"] = $ads ? $ads['AdMob_Native_Ad_Unit_ID']: '';

        $response_ads_native_item["name"] = "ADMIN_NATIVE_LINES";
        $response_ads_native_item["value"] = 6;


        $response_ads_native_type["name"] = "ADMIN_NATIVE_TYPE";
        $response_ads_native_type["value"] = "BOTH";

        $code="200";
        $response["name"]="update";
        $response["value"]="App on update";
        $message="";

        $errors[]=$response;

        $errors[]=$response_app_id;
        $errors[]=$response_publisher_id;
        $errors[]=$response_ads_rewarded;
        $errors[]=$response_ads_interstitial_admob_id;
        $errors[]=$response_ads_interstitial_facebook_id;
        $errors[]=$response_ads_interstitial_type;
        $errors[]=$response_ads_interstitial_click;
        $errors[]=$response_ads_banner_admob_id;
        $errors[]=$response_ads_banner_facebook_id;
        $errors[]=$response_ads_banner_type;
        $errors[]=$response_ads_native_facebook_id;
        $errors[]=$response_ads_native_admob_id;
        $errors[]=$response_ads_native_item ;
        $errors[]=$response_ads_native_type;

        $error=array(
            "code"=>$code,
            "message"=>$message,
            "values"=>$errors,
        );
        return new Response(json_encode($error));

    }

    public function first(){
        $page_limit = 10;
        $limit= 0;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->paginate($page_limit)
                ->toArray();

            $category = CategoryManage::with('wallpaper')
                ->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper', '>', 0)
                ->where('site_name', $domain)
                ->where('tbl_category_manages.checked_ip', 1)
                ->withCount('wallpaper')
                ->get()->toArray();
        } else {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
//                ->setFirstResult($page_limit * $limit)
//                ->setMaxResults($page_limit)

//                ->offset($limit)
//                ->get()
                ->paginate($page_limit)->toArray();
            $category = CategoryManage::with('wallpaper')
                ->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper', '>', 0)
                ->where('site_name', $domain)
                ->where('tbl_category_manages.checked_ip', 0)
                ->withCount('wallpaper')
                ->get()->toArray();
        }




        $data_arr['categories'] = $this->getCategories($category);
//        $data_arr['slides'] = $this->slidesArray($category);
//        $data_arr['packs'] = $this->packsArray($category);
        $data_arr['wallpapers'] = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

    }

    public function categoryAll(){
//
//        $page_limit = 10;
//        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
//            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
//                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
//                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
//                    ->where('site_name', $domain)
//                    ->where('tbl_category_manages.checked_ip', 1)
//                    ->select('tbl_category_manages.*');
//            })
//                ->limit($page_limit)
//                ->offset($limit)
//                ->get()
//                ->toArray();

            $category = CategoryManage
                ::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper', '>', 0)
                ->where('site_name', $domain)
                ->where('tbl_category_manages.checked_ip', 1)
                ->withCount('wallpaper')
                ->get()->toArray();
        } else {
//            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
//                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
//                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
//                    ->where('site_name', $domain)
//                    ->where('tbl_category_manages.checked_ip', 0)
//                    ->select('tbl_category_manages.*');
//            })
//                ->limit($page_limit)
//                ->offset($limit)
//                ->get()
//                ->toArray();
            $category = CategoryManage
                ::leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper', '>', 0)
                ->where('site_name', $domain)
                ->where('tbl_category_manages.checked_ip', 0)
                ->withCount('wallpaper')
                ->get()->toArray();
        }

//        dd($category);

//        $endpoint = " https://wallpapers.virmana.com/api/category/all/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $endpoint = "https://wallpapers.virmana.com/api/wallpaper/all/views/0/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $response = Http::get( $endpoint);
//        dd($response->json());



        $data_arr = $this->getCategories($category);
        $a = '[{"id":1,"title":"Animals","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/612402c58ac099ef747fe9a402988a47.jpeg"},{"id":2,"title":"Amoled","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/82a7d40f8b5c8d10072a1ddcb00e2b8f.jpeg"},{"id":3,"title":"Cars","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/44fbbbc0c2a1aa8242d723661c00be07.jpeg"},{"id":4,"title":"Gaming","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/4a2da45b9f19c62709115ece8aacc9a0.png"},{"id":5,"title":"Food","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/879b8829ecf7076e2472926cb6a6a26a.jpeg"},{"id":6,"title":"Anime","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/54c869eb9b6baf68c71f68da57712bc5.jpeg"},{"id":7,"title":"Nature","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/420d8e47008b3d2157bc1f7a7ace6e77.jpeg"},{"id":8,"title":"Minimal","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/80191c673eb2e1fbd28bc6539f4d7d1a.jpeg"},{"id":9,"title":"Space","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/6b9a6783306fba49fd6f4a4ef6cd7fa5.jpeg"},{"id":10,"title":"Sport","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b4f96bb607e3d3a9ad77884f5937883f.jpeg"}]';
//        dd($data_arr,json_decode($a,true));
        return json_encode($data_arr);

    }

    public function wallpapersAll($order,$page){
        $page_limit = 10;
        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy($order, 'desc')
                ->paginate($page_limit)
                ->toArray();

        } else {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy($order, 'desc')
                ->paginate($page_limit)
                ->toArray();
        }

        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersRandom($page){
        $page_limit = 10;
        $limit= 0 * $page_limit;
        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $wallpaper = Wallpapers::whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        } else {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        }

        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersByCategory($page,$category){
        $page_limit = 10;
        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {


            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)

//                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->where('cate_id', $category)
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        } else {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->where('cate_id', $category)
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        }
        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

    }

    public function wallpapersBysearch($page,$query){
        $page_limit = 10;
        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {


            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->where('name', 'like', '%' . $query . '%')
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        } else {
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->where('name', 'like', '%' . $query . '%')
                ->inRandomOrder()
                ->paginate($page_limit)
                ->toArray();

        }
        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

    }

    public function getCategories($data){
        $jsonObj= [];
        foreach ($data as $item){
            $data_arr['id'] = $item['id'];
            $data_arr['title'] = $item['category_name'];
            $data_arr['image'] = asset('storage/categories/'.$item['image']);
            $data_arr['extension'] = 'jpeg';
            $data_arr['test'] = (string)$item['view_count'];

            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    private  function getWallpaper($data){
        $jsonObj = [];
        foreach ($data['data'] as $item){

            $data_arr['id'] = $item['id'];
            $data_arr['kind'] = $item['image_extension'] != 'image/gif' ? 'image' : 'gif';
            $data_arr['title'] = $item['name'];
            $data_arr['description'] = $item['name'];
            $data_arr['category'] = $item['category']['category_name'];
//            $data_arr['color'] =  '000000';
            $data_arr['color'] =  substr(md5(rand()), 0, 6);

            $data_arr['downloads'] = rand(500,1000);

            $data_arr['views'] = $item['view_count'];
            $data_arr['shares'] = rand(500,1000);
            $data_arr['sets'] = rand(500,1000);

            $data_arr['type'] = $item['image_extension'];
            $data_arr['extension'] = $item['image_extension'];

            $data_arr['thumbnail'] = asset('storage/wallpapers/thumbnail/'.$item['thumbnail_image']);
            $data_arr['image'] = asset('storage/wallpapers/detail/'.$item['image']);
            $data_arr['original'] = asset('storage/wallpapers/download/'.$item['origin_image']);
            $data_arr['created'] = Carbon::parse($item['created_at'])->format('Y-m-d') ;
            $data_arr['tags'] = null;
            array_push($jsonObj,$data_arr);
        }

        return $jsonObj;
    }

//    public function packsArray($data){
//        $jsonObj= [];
////        for ($i = 1; $i < 6; $i++){
////        $jsonObj= [];
//        foreach ($data as $item){
//            $data_arr['id'] = $item->id;
//            $data_arr['title'] = $item->category_name;
//            foreach ($item->wallpaper->take(5) as $value){
//                $data_arr['images'][] = asset('storage/wallpapers/thumbnail/'.$value->thumbnail_image);
//            }
//            array_push($jsonObj,$data_arr);
//        }
//        return $jsonObj;
//    }
//
//    public function slidesArray($data){
//
//        $jsonObj= [];
//        foreach ($data as $item){
//            $data_arr['id'] = $item['id'];
//            $data_arr['title'] = base64_encode($item['category_name']);
//            $data_arr['type'] = "1";
//            $data_arr['image'] = asset('storage/categories/'.$item['image']);
//            array_push($jsonObj,$data_arr);
//        }
//        return $jsonObj;
//    }
}
