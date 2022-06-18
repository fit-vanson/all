<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteManage;
use App\Models\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use kornrunner\Blurhash\Blurhash;
use League\ColorExtractor\Palette;
use function GuzzleHttp\Promise\all;

class ApiV4Controller extends Controller
{
    public function index(){
        dd(1);
    }

    public function admob(){
        $domain = $_SERVER['SERVER_NAME'];
        $site =  SiteManage::where('site_name', $domain)->first();
        $ads = json_decode($site->ads,true);


        $result = [
            'provider' => $ads['ads_provider'],

            'admob_banner' => $ads['AdMob_Banner_Ad_Unit_ID'],
            'admob_reward' => $ads['AdMob_App_Reward_Ad_Unit_ID'],
            'admob_open' => $ads['AdMob_App_Open_Ad_Unit_ID'],
            'admob_native' => $ads['AdMob_Native_Ad_Unit_ID'],
            'admob_interstitial' => $ads['AdMob_Interstitial_Ad_Unit_ID'],

            'applovin_banner' => $ads['applovin_banner'],
            'applovin_interstitial' => $ads['applovin_interstitial'],
            'applovin_reward' => $ads['applovin_reward'],

            'startapp_id' => $ads['startapp_id'],

            'ironsource_id' => $ads['ironsource_id'],

            'banner_enable' => $site->ad_switch,
            'interstitial_enable' => $site->ad_switch,
            'reward_enable' => $site->ad_switch,
            'open_enable' => $site->ad_switch,
        ];

        return $result;

    }
    public function settings(){

        $settings = [
            "onesignal_id"=> "01f96de5-e775-43a8-b9d0-91a720d65912",
            "onesignal_rest"=> "NmMyOGNmNzQtNWM4MC00MjgxLWJiOTEtNTljNjA0YmI3YjA4",
            "packagename"=> "https=>//play.google.com/store/apps/dev?id=5703447331110116266",
            "privacy"=> "https=>//google.com",
            "layout"=> "dark-layout",
            "server_key"=> "XjjXvKKAxjYmJjjOdFSKdAOlZwTkvlQrXRShNQlIzRedUzPifp",
            "wallpaper_columns"=> "3",
            "show_view_count"=> "false",
            "show_categories"=> "true",
            "setting_icon"=> "icon/1649458789_06bbb5ee95a644288cdb.png",
            "home_icon"=> "icon/1649681235_555f82c4bc2ec4b64eb2.png",
            "categories_icon"=> "icon/1649681235_e2fb6d0d3a9eb20749cc.png",
            "popular_icon"=> "icon/1649681235_e232efe0fe4cbcc039ad.png",
            "favourite_icon"=> "icon/1649681235_dd3df73bc9e08ec4e699.png",
            "back_icon"=> "icon/1649648137_4f61c645b41a456a3460.png",
            "download_icon"=> "icon/1649648137_02f2c6b2aa2168c0dc85.png",
            "set_wallpaper_icon"=> "icon/1649680653_5e6fb36cd6418c1f575e.png",
            "favourite_enable_icon"=> "icon/1649648137_09fd2adad5969e30aea6.png",
            "favourite_disable_icon"=> "icon/1649648137_41d6e7c4b84867caff64.png",
            "background_color"=> "#191B21",
            "header_color"=> "#0F1013",
            "filter_icon"=> "icon/1649613118_8d1ea92b2aca4a160143.png"
        ];

        return json_encode($settings);

    }
    public function home(){
        $domain = $_SERVER['SERVER_NAME'];
        $data = array();
        if (checkBlockIp()) {
            array_push($data, [
                'name' => 'latest', 'data' => $this->getWallpaper('id',$domain,1,'<>',20)
            ]);
            array_push($data, [
                'name' => 'popular', 'data' => $this->getWallpaper('view_count',$domain,1,'<>',20)
            ]);
            array_push($data, [
                'name' => 'random', 'data' => $this->getWallpaper('name',$domain,1,'<>',20)
            ]);
            array_push($data, [
                'name' => 'downloaded', 'data' => $this->getWallpaper('like_count',$domain,1,'<>',20)
            ]);
            array_push($data, [
                'name' => 'live', 'data' => $this->getWallpaper('id',$domain,1,'=',20)
            ]);

        } else {
            array_push($data, [
                'name' => 'latest', 'data' => $this->getWallpaper('id',$domain,0,'<>',20)
            ]);
            array_push($data, [
                'name' => 'popular', 'data' => $this->getWallpaper('view_count',$domain,0,'<>',20)
            ]);
            array_push($data, [
                'name' => 'random', 'data' => $this->getWallpaper('name',$domain,0,'<>',20)
            ]);
            array_push($data, [
                'name' => 'downloaded', 'data' => $this->getWallpaper('like_count',$domain,0,'<>',20)
            ]);
            array_push($data, [
                'name' => 'live', 'data' => $this->getWallpaper('id',$domain,0,'=',20)
            ]);
        }
        return $data;

    }



    private  function getWallpaper($order, $domain, $checkBlock, $gif, $limit){
        $result = Wallpapers::with('category')->whereHas('category', function ($q) use ($checkBlock, $domain) {
            $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->where('site_name', $domain)
                ->where('tbl_category_manages.checked_ip', $checkBlock)
                ->select('tbl_category_manages.*');
            })
            ->where('image_extension', $gif, 'image/gif')
            ->orderByDesc($order)
            ->limit($limit)->get();

        $jsonObj =[];
        foreach ($result as $item){
            $data_arr = $this->jsonWallpaper($item);

            array_push($jsonObj,json_decode(json_encode($data_arr), FALSE));

        }
        return $jsonObj;
    }

    public function viewWallpaper(Request $request){
        $model = Wallpapers::find($request['id']);
        $model->view_count = $model->view_count + 1;
        $model->save();
        return  $this->jsonWallpaper($model);
    }

    private function jsonWallpaper($item){
        $data_arr = [
            'id' =>$item->id,
            'cid' =>$item->cate_id,
            'image' => asset('storage/wallpapers/download/'.$item->origin_image),
            'type' =>$item->image_extension == 'image/jpeg' ? 'IMAGE' : 'GIF'  ,
            'premium' => 0,
            'tags' =>$item->name,
            'view' =>$item->view_count,
            'download' =>$item->like_count,
        ];
        return collect($data_arr);

    }

    public function wallpaper(){
        $domain = $_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $result = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
                })
                ->orderByDesc('id')
                ->paginate(21);
        }else{
            $result = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
                })
                ->orderByDesc('id')
                ->paginate(21);

        }
        $jsonObj =[];
        foreach ($result as $item ){
            $data_arr = $this->jsonWallpaper($item);
            array_push($jsonObj,json_decode(json_encode($data_arr), FALSE));
        }



        $data['current_page'] = $result->currentPage();
        $data['last_page'] = $result->lastPage();
        $data['total'] = $result->total();

        $data['data'] = $jsonObj;

        return $data;
    }




}
