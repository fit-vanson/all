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
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
//                ->setFirstResult($page_limit * $limit)
//                ->setMaxResults($page_limit)
//                ->offset($limit)
//                ->get()
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
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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

//        dd($data_arr);
//        $d = '{"categories":[{"id":2,"title":"Amoled","image":"82a7d40f8b5c8d10072a1ddcb00e2b8f.jpeg","extension":"jpeg","test":"12539"},{"id":9,"title":"Space","image":"6b9a6783306fba49fd6f4a4ef6cd7fa5.jpeg","extension":"jpeg","test":"8464"},{"id":4,"title":"Gaming","image":"4a2da45b9f19c62709115ece8aacc9a0.png","extension":"png","test":"6327"},{"id":3,"title":"Cars","image":"44fbbbc0c2a1aa8242d723661c00be07.jpeg","extension":"jpeg","test":"6249"},{"id":7,"title":"Nature","image":"420d8e47008b3d2157bc1f7a7ace6e77.jpeg","extension":"jpeg","test":"3956"},{"id":10,"title":"Sport","image":"b4f96bb607e3d3a9ad77884f5937883f.jpeg","extension":"jpeg","test":"2908"},{"id":8,"title":"Minimal","image":"80191c673eb2e1fbd28bc6539f4d7d1a.jpeg","extension":"jpeg","test":"2445"},{"id":1,"title":"Animals","image":"612402c58ac099ef747fe9a402988a47.jpeg","extension":"jpeg","test":"2146"},{"id":6,"title":"Anime","image":"54c869eb9b6baf68c71f68da57712bc5.jpeg","extension":"jpeg","test":"1154"},{"id":5,"title":"Food","image":"879b8829ecf7076e2472926cb6a6a26a.jpeg","extension":"jpeg","test":"1140"}],"slides":[{"id":1,"title":"QmVzdCBjYXJzIHdhbGxwYXBlcg==","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/ff2e0041e55a16720b105306d5d06fbb.jpeg","category":{"id":3,"title":"Cars","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/44fbbbc0c2a1aa8242d723661c00be07.jpeg"}},{"id":2,"title":"R2FtaW5ncyB3YWxscGFwZXJz","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/c9a0f3b0d31872a24f221763d3b37cfa.jpeg","category":{"id":4,"title":"Gaming","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/4a2da45b9f19c62709115ece8aacc9a0.png"}},{"id":3,"title":"TGl2ZSBBbmltYWxzIFdhbGxwYXBlcnM=","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/6a1a601e82a28a061abcddedecaa55c5.jpeg","category":{"id":1,"title":"Animals","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/612402c58ac099ef747fe9a402988a47.jpeg"}}],"packs":[{"id":7,"title":"Galaxy S10","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b5a9ab346b8801e84581eec80c59d131.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/93d546c1d77c5581dc21da9334437d85.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/5604aa323825f7c638eb7f3252e197b8.png"]},{"id":6,"title":"iPhoneX","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/e9df6d5ef5b3b143a096296563ba0cac.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b8d1092f9838147b37cbd9831e5ec767.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/7c0178c4dd6e8e70d85ae1f0b692791d.jpeg"]},{"id":3,"title":"Ramadan","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/dfe2e35f628502faf27ff107adafbc8e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c8dd82bd6cc49a07a971b5ab0d15dc26.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/5161da87cb0f1ea02cd82332578b7d62.jpeg"]},{"id":2,"title":"PUBG","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c1f7e91093b0863d80bb103a2649aa2e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c0e887312b2cce43940be7ef374e371e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c1e0b09a34e29259ccdc66c0b6a202ff.jpeg"]},{"id":1,"title":"One Plus 7","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/207d773cdccbd8b7d3c81ad5fee9eea0.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/f3972a103e8b135e5d0eabb716a1e647.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/91cf01a3de0ce3cb38c7f1c405c0d31e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ecfca61e8363333a52ad8a794b1663a1.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/8accc37ef9c995ebd73e046631451703.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/a9c496f0917788642d789c37c3f35715.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/7e5938a103094a1ca0cb98ba05a75b57.png","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ffa0cfd36c411baf117da3cca04a0260.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/1fd0551e8f11d77c393658755163c3da.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/b4ef90e89d0513bbfc8299a3e52a2997.png","https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/category_thumb_api\/uploads\/png\/74c3c11899d2af06c442670c1906661b.png","https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/category_thumb_api\/uploads\/png\/b3e151e9ad1e7d9b2f5ad7b7e5c55ec4.png"]}],"wallpapers":[{"id":754,"kind":"video","title":"Wave wallapper","description":"Wave wallapper","review":false,"premium":false,"color":"4a9aa8","size":"1M","resolution":"1080x720","comment":true,"comments":2,"downloads":5,"views":44,"shares":2,"sets":12,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/png\/74be26e986f7b75e6760b5b05ebbbcfd.png","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/png\/74be26e986f7b75e6760b5b05ebbbcfd.png","original":"http:\/\/awakiapps.xyz\/Abstract.mp4","created":"2 months ago","tags":null},{"id":720,"kind":"image","title":"King Kong .jpg","description":"fuaufz","review":false,"premium":false,"color":"815f5c","size":"152 KB","resolution":"623 X 1350","comment":true,"comments":1,"downloads":36,"views":232,"shares":31,"sets":44,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","created":"1 year ago","tags":null},{"id":719,"kind":"image","title":"Dolar 2.jpg","description":"tdidy","review":false,"premium":false,"color":"c0c0c0","size":"443.33 KB","resolution":"886 X 1920","comment":true,"comments":1,"downloads":14,"views":122,"shares":10,"sets":27,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","created":"1 year ago","tags":null},{"id":718,"kind":"image","title":"Night street .jpg","description":"chu","review":false,"premium":false,"color":"424956","size":"158.39 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":13,"views":81,"shares":9,"sets":12,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","created":"1 year ago","tags":null},{"id":717,"kind":"image","title":"Nature Landscape.jpg","description":"best","review":false,"premium":false,"color":"5c5b30","size":"145.05 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":12,"views":82,"shares":11,"sets":22,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","created":"1 year ago","tags":null},{"id":716,"kind":"image","title":"Nature Landscape.jpg","description":"tvh","review":false,"premium":false,"color":"5c5b30","size":"145.05 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":11,"views":57,"shares":3,"sets":17,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","created":"1 year ago","tags":null},{"id":702,"kind":"image","title":"Screenshot_20210221_005620_com.instagram.android.jpg","description":"","review":false,"premium":false,"color":"232c47","size":"424.52 KB","resolution":"1080 X 2340","comment":true,"comments":0,"downloads":14,"views":128,"shares":10,"sets":16,"trusted":false,"user":"Abdessamad Karimi","userid":1045,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjpZD7oDCjepeJahIo6pC-tM9zUl7-uDNo1pYDpfg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","created":"1 year ago","tags":null},{"id":658,"kind":"image","title":"wp4901378.jpg","description":"Bollywood, Indian","review":false,"premium":false,"color":"b27157","size":"91.9 KB","resolution":"960 X 1280","comment":true,"comments":0,"downloads":29,"views":255,"shares":28,"sets":44,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","created":"1 year ago","tags":null},{"id":654,"kind":"image","title":"Memes Time.jpg","description":"Hrithik Roshan","review":false,"premium":false,"color":"927161","size":"121.14 KB","resolution":"853 X 1200","comment":true,"comments":0,"downloads":10,"views":189,"shares":18,"sets":19,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","created":"1 year ago","tags":null},{"id":653,"kind":"image","title":"Wolverine.jpg","description":"X-men","review":false,"premium":false,"color":"aa9d81","size":"268.98 KB","resolution":"720 X 1440","comment":true,"comments":0,"downloads":10,"views":119,"shares":7,"sets":13,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","created":"1 year ago","tags":null},{"id":649,"kind":"image","title":"ab5f2d04999de52575caffd1b2551c56.jpg","description":"","review":false,"premium":false,"color":"2b647c","size":"103.23 KB","resolution":"486 X 720","comment":true,"comments":2,"downloads":16,"views":155,"shares":14,"sets":25,"trusted":true,"user":"Music World","userid":958,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14Gg0n_6op1jnDuKQi_tKzM6jmYqFIhAOKicl4oC2","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","created":"1 year ago","tags":null},{"id":621,"kind":"image","title":"Superman Crispy Walls","description":"","review":false,"premium":false,"color":"1c1f4b","size":"1.2 MB","resolution":"1080 X 2160","comment":true,"comments":1,"downloads":40,"views":299,"shares":21,"sets":64,"trusted":true,"user":"Amit yadav","userid":900,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgwmdlsdDVZRkZJT-maWlMEhgZOF232eYwnU07okQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","created":"1 year ago","tags":null},{"id":556,"kind":"image","title":"NAGALAND FLAG","description":"NAGALAND FLAG","review":false,"premium":false,"color":"4a7c33","size":"36.87 KB","resolution":"452 X 678","comment":true,"comments":3,"downloads":120,"views":909,"shares":97,"sets":122,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","created":"1 year ago","tags":null},{"id":555,"kind":"image","title":"FitnessGirl.jpg","description":"","review":false,"premium":false,"color":"caa37b","size":"84.24 KB","resolution":"1060 X 1051","comment":true,"comments":1,"downloads":93,"views":778,"shares":52,"sets":77,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","created":"1 year ago","tags":null},{"id":554,"kind":"video","title":"Enjoy","description":"ENJOY","review":false,"premium":false,"color":"464253","size":"791.54 KB","resolution":"307 X 384","comment":true,"comments":2,"downloads":152,"views":881,"shares":83,"sets":179,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4f86ea001331c76904b04d66b182db3b.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4f86ea001331c76904b04d66b182db3b.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/mp4\/804715e391e9c1ea6cd03eeff4025e46.mp4","created":"1 year ago","tags":null},{"id":553,"kind":"image","title":"fucking","description":"sex","review":false,"premium":false,"color":"33001a","size":"192.4 KB","resolution":"1080 X 760","comment":true,"comments":0,"downloads":184,"views":1909,"shares":97,"sets":90,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","created":"1 year ago","tags":null},{"id":549,"kind":"image","title":"b9c1dc6daad1f0362414650b98f488ba.jpg","description":"","review":false,"premium":false,"color":"67635b","size":"60.96 KB","resolution":"564 X 564","comment":true,"comments":0,"downloads":86,"views":775,"shares":69,"sets":118,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","created":"1 year ago","tags":null},{"id":543,"kind":"image","title":"youtube","description":"jst a test ;p","review":false,"premium":false,"color":"b3b3b3","size":"2.54 KB","resolution":"54 X 65","comment":true,"comments":0,"downloads":40,"views":580,"shares":18,"sets":25,"trusted":true,"user":"Is Boto","userid":696,"userimage":"https:\/\/lh3.googleusercontent.com\/-XdUIqdMkCWA\/AAAAAAAAAAI\/AAAAAAAAAAA\/4252rscbv5M\/photo.jpg","type":"image\/png","extension":"png","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","original":"https:\/\/wallpapers.virmana.com\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","created":"1 year ago","tags":null},{"id":237,"kind":"image","title":"IMG_20191012_212213_627.jpg","description":"","review":false,"premium":false,"color":"576a6a","size":"70.6 KB","resolution":"401 X 401","comment":true,"comments":4,"downloads":733,"views":6458,"shares":292,"sets":608,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","created":"2 years ago","tags":null},{"id":229,"kind":"image","title":"Shooting stars11472_rectangle.jpg","description":"","review":false,"premium":false,"color":"302fb3","size":"113.7 KB","resolution":"1440 X 2560","comment":true,"comments":4,"downloads":1953,"views":9169,"shares":612,"sets":1461,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","created":"2 years ago","tags":null},{"id":228,"kind":"image","title":"Ramadan_42.jpeg","description":"","review":false,"premium":false,"color":"a19a4b","size":"223.97 KB","resolution":"1440 X 2960","comment":true,"comments":3,"downloads":167,"views":1499,"shares":69,"sets":119,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","created":"2 years ago","tags":null},{"id":150,"kind":"video","title":"fortlives808","description":"","review":false,"premium":false,"color":"c0a59d","size":"1.95 MB","resolution":"236 X 512","comment":true,"comments":4,"downloads":705,"views":6298,"shares":306,"sets":905,"trusted":true,"user":"Live Wallpapers","userid":143,"userimage":"https:\/\/lh3.googleusercontent.com\/-XdUIqdMkCWA\/AAAAAAAAAAI\/AAAAAAAAAAA\/4252rscbv5M\/photo.jpg","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4ac1c30dc5b935e59f14103559399219.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4ac1c30dc5b935e59f14103559399219.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/mp4\/b9f923aef6bfe359ddb7879c4ec50e4c.mp4","created":"2 years ago","tags":null},{"id":63,"kind":"image","title":"aerial shot bird","description":null,"review":false,"premium":false,"color":"533E21","size":"2.15 MB","resolution":"2432 X 3648","comment":true,"comments":14,"downloads":528,"views":3995,"shares":185,"sets":440,"trusted":false,"user":"Jimes diamo","userid":2,"userimage":"https:\/\/platform-lookaside.fbsbx.com\/platform\/profilepic\/?asid=2474801685872271&height=200&width=200&ext=1573584924&hash=AeRg0TAtNhUyDVoS","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_image_api\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","created":"2 years ago","tags":"nature,aerial,shot,bird"},{"id":60,"kind":"gif","title":"Rudolp Jumping Rope","description":null,"review":false,"premium":false,"color":"B5AD81","size":"82.42 KB","resolution":"800 X 600","comment":true,"comments":5,"downloads":254,"views":2652,"shares":120,"sets":472,"trusted":false,"user":"Jimes diamo","userid":2,"userimage":"https:\/\/platform-lookaside.fbsbx.com\/platform\/profilepic\/?asid=2474801685872271&height=200&width=200&ext=1573584924&hash=AeRg0TAtNhUyDVoS","type":"image\/gif","extension":"gif","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","original":"https:\/\/wallpapers.virmana.com\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","created":"2 years ago","tags":null},{"id":58,"kind":"image","title":"Car night","description":null,"review":false,"premium":false,"color":"9B7663","size":"4.6 MB","resolution":"1960 X 4032","comment":true,"comments":8,"downloads":1622,"views":10038,"shares":438,"sets":1208,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","created":"2 years ago","tags":"car,night"},{"id":57,"kind":"image","title":"Colored wall","description":null,"review":false,"premium":false,"color":"1e2057","size":"919.59 KB","resolution":"1555 X 1377","comment":true,"comments":3,"downloads":938,"views":5386,"shares":349,"sets":708,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_image_api\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","created":"2 years ago","tags":"color"},{"id":56,"kind":"video","title":"Dark and rainy","description":null,"review":false,"premium":false,"color":"634769","size":"9.91 MB","resolution":"1080 X 1920","comment":true,"comments":1,"downloads":662,"views":3873,"shares":231,"sets":841,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"video\/quicktime","extension":"qt","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d6f15a4a3c8c838c403d12f7823ce28f.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d6f15a4a3c8c838c403d12f7823ce28f.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/qt\/2f9fc85a532eb2c07e26027dff37821f.qt","created":"2 years ago","tags":"dark,rainy"},{"id":55,"kind":"gif","title":"China street","description":null,"review":false,"premium":false,"color":"97bcbc","size":"441.04 KB","resolution":"864 X 1536","comment":true,"comments":1,"downloads":308,"views":2603,"shares":103,"sets":327,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/gif","extension":"gif","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","original":"https:\/\/wallpapers.virmana.com\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","created":"2 years ago","tags":"street,china"},{"id":53,"kind":"image","title":"Galaxy S10","description":null,"review":false,"premium":false,"color":"A1B566","size":"226.2 KB","resolution":"3040 X 3040","comment":true,"comments":2,"downloads":473,"views":3287,"shares":144,"sets":388,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","created":"2 years ago","tags":"samsung,galaxy,s10"},{"id":52,"kind":"image","title":"Galaxy S10","description":null,"review":false,"premium":false,"color":"371676","size":"123.96 KB","resolution":"3040 X 3040","comment":true,"comments":0,"downloads":357,"views":2241,"shares":130,"sets":298,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","created":"2 years ago","tags":"samsung,galaxy,s10"}]}';

//        dd(json_decode($d,true), $data_arr);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);




//        $a = '{"categories":[{"id":2,"title":"Amoled","image":"82a7d40f8b5c8d10072a1ddcb00e2b8f.jpeg","extension":"jpeg","test":"12539"},{"id":9,"title":"Space","image":"6b9a6783306fba49fd6f4a4ef6cd7fa5.jpeg","extension":"jpeg","test":"8464"},{"id":4,"title":"Gaming","image":"4a2da45b9f19c62709115ece8aacc9a0.png","extension":"png","test":"6327"},{"id":3,"title":"Cars","image":"44fbbbc0c2a1aa8242d723661c00be07.jpeg","extension":"jpeg","test":"6249"},{"id":7,"title":"Nature","image":"420d8e47008b3d2157bc1f7a7ace6e77.jpeg","extension":"jpeg","test":"3956"},{"id":10,"title":"Sport","image":"b4f96bb607e3d3a9ad77884f5937883f.jpeg","extension":"jpeg","test":"2908"},{"id":8,"title":"Minimal","image":"80191c673eb2e1fbd28bc6539f4d7d1a.jpeg","extension":"jpeg","test":"2445"},{"id":1,"title":"Animals","image":"612402c58ac099ef747fe9a402988a47.jpeg","extension":"jpeg","test":"2146"},{"id":6,"title":"Anime","image":"54c869eb9b6baf68c71f68da57712bc5.jpeg","extension":"jpeg","test":"1154"},{"id":5,"title":"Food","image":"879b8829ecf7076e2472926cb6a6a26a.jpeg","extension":"jpeg","test":"1140"}],"slides":[{"id":1,"title":"QmVzdCBjYXJzIHdhbGxwYXBlcg==","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/ff2e0041e55a16720b105306d5d06fbb.jpeg","category":{"id":3,"title":"Cars","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/44fbbbc0c2a1aa8242d723661c00be07.jpeg"}},{"id":2,"title":"R2FtaW5ncyB3YWxscGFwZXJz","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/c9a0f3b0d31872a24f221763d3b37cfa.jpeg","category":{"id":4,"title":"Gaming","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/4a2da45b9f19c62709115ece8aacc9a0.png"}},{"id":3,"title":"TGl2ZSBBbmltYWxzIFdhbGxwYXBlcnM=","type":"1","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/slide_thumb\/uploads\/jpeg\/6a1a601e82a28a061abcddedecaa55c5.jpeg","category":{"id":1,"title":"Animals","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/612402c58ac099ef747fe9a402988a47.jpeg"}}],"packs":[{"id":7,"title":"Galaxy S10","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b5a9ab346b8801e84581eec80c59d131.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/93d546c1d77c5581dc21da9334437d85.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/5604aa323825f7c638eb7f3252e197b8.png"]},{"id":6,"title":"iPhoneX","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/e9df6d5ef5b3b143a096296563ba0cac.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b8d1092f9838147b37cbd9831e5ec767.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/7c0178c4dd6e8e70d85ae1f0b692791d.jpeg"]},{"id":3,"title":"Ramadan","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/dfe2e35f628502faf27ff107adafbc8e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c8dd82bd6cc49a07a971b5ab0d15dc26.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/5161da87cb0f1ea02cd82332578b7d62.jpeg"]},{"id":2,"title":"PUBG","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c1f7e91093b0863d80bb103a2649aa2e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c0e887312b2cce43940be7ef374e371e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/c1e0b09a34e29259ccdc66c0b6a202ff.jpeg"]},{"id":1,"title":"One Plus 7","images":["https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/207d773cdccbd8b7d3c81ad5fee9eea0.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/f3972a103e8b135e5d0eabb716a1e647.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/91cf01a3de0ce3cb38c7f1c405c0d31e.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ecfca61e8363333a52ad8a794b1663a1.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/8accc37ef9c995ebd73e046631451703.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/a9c496f0917788642d789c37c3f35715.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/7e5938a103094a1ca0cb98ba05a75b57.png","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/ffa0cfd36c411baf117da3cca04a0260.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/1fd0551e8f11d77c393658755163c3da.jpeg","https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/b4ef90e89d0513bbfc8299a3e52a2997.png","https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/category_thumb_api\/uploads\/png\/74c3c11899d2af06c442670c1906661b.png","https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/category_thumb_api\/uploads\/png\/b3e151e9ad1e7d9b2f5ad7b7e5c55ec4.png"]}],"wallpapers":[{"id":754,"kind":"video","title":"Wave wallapper","description":"Wave wallapper","review":false,"premium":false,"color":"4a9aa8","size":"1M","resolution":"1080x720","comment":true,"comments":2,"downloads":5,"views":44,"shares":2,"sets":12,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/png\/74be26e986f7b75e6760b5b05ebbbcfd.png","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/png\/74be26e986f7b75e6760b5b05ebbbcfd.png","original":"http:\/\/awakiapps.xyz\/Abstract.mp4","created":"2 months ago","tags":null},{"id":720,"kind":"image","title":"King Kong .jpg","description":"fuaufz","review":false,"premium":false,"color":"815f5c","size":"152 KB","resolution":"623 X 1350","comment":true,"comments":1,"downloads":36,"views":232,"shares":31,"sets":44,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/52628145c3f677bb349526519735c1d4.jpeg","created":"1 year ago","tags":null},{"id":719,"kind":"image","title":"Dolar 2.jpg","description":"tdidy","review":false,"premium":false,"color":"c0c0c0","size":"443.33 KB","resolution":"886 X 1920","comment":true,"comments":1,"downloads":14,"views":122,"shares":10,"sets":27,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/b5ce1ac5fb2f9c1fe063cf6e861c1faa.jpeg","created":"1 year ago","tags":null},{"id":718,"kind":"image","title":"Night street .jpg","description":"chu","review":false,"premium":false,"color":"424956","size":"158.39 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":13,"views":81,"shares":9,"sets":12,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/2c87599d3f35567403585d3fb54717b9.jpeg","created":"1 year ago","tags":null},{"id":717,"kind":"image","title":"Nature Landscape.jpg","description":"best","review":false,"premium":false,"color":"5c5b30","size":"145.05 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":12,"views":82,"shares":11,"sets":22,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/d1ddc38ee7fca47ec30298cbca370f1e.jpeg","created":"1 year ago","tags":null},{"id":716,"kind":"image","title":"Nature Landscape.jpg","description":"tvh","review":false,"premium":false,"color":"5c5b30","size":"145.05 KB","resolution":"886 X 1920","comment":true,"comments":0,"downloads":11,"views":57,"shares":3,"sets":17,"trusted":true,"user":"Anday wala Burger","userid":1079,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjaI-oI_uhnWKi5KkQM6InPTtWcnbf7seKqFHA7GA","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/18ca41bdbdf6e20861d830ad2f8e6417.jpeg","created":"1 year ago","tags":null},{"id":702,"kind":"image","title":"Screenshot_20210221_005620_com.instagram.android.jpg","description":"","review":false,"premium":false,"color":"232c47","size":"424.52 KB","resolution":"1080 X 2340","comment":true,"comments":0,"downloads":14,"views":128,"shares":10,"sets":16,"trusted":false,"user":"Abdessamad Karimi","userid":1045,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GjpZD7oDCjepeJahIo6pC-tM9zUl7-uDNo1pYDpfg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/3a704b20d3d0736a81d3ebf21ed9f6eb.jpeg","created":"1 year ago","tags":null},{"id":658,"kind":"image","title":"wp4901378.jpg","description":"Bollywood, Indian","review":false,"premium":false,"color":"b27157","size":"91.9 KB","resolution":"960 X 1280","comment":true,"comments":0,"downloads":29,"views":255,"shares":28,"sets":44,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/4e100c4597c985835bed045d771b08fd.jpeg","created":"1 year ago","tags":null},{"id":654,"kind":"image","title":"Memes Time.jpg","description":"Hrithik Roshan","review":false,"premium":false,"color":"927161","size":"121.14 KB","resolution":"853 X 1200","comment":true,"comments":0,"downloads":10,"views":189,"shares":18,"sets":19,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/440ae1101b9c3d4092697af4e0a4cfa3.jpeg","created":"1 year ago","tags":null},{"id":653,"kind":"image","title":"Wolverine.jpg","description":"X-men","review":false,"premium":false,"color":"aa9d81","size":"268.98 KB","resolution":"720 X 1440","comment":true,"comments":0,"downloads":10,"views":119,"shares":7,"sets":13,"trusted":true,"user":"Memes Time","userid":959,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GhqdDmAaLI5ywfC7hV-r1C9qawQw0Z2H9rCKZxtxg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/3a48ea295871e5a8ac6a2ca3fb76f54d.jpeg","created":"1 year ago","tags":null},{"id":649,"kind":"image","title":"ab5f2d04999de52575caffd1b2551c56.jpg","description":"","review":false,"premium":false,"color":"2b647c","size":"103.23 KB","resolution":"486 X 720","comment":true,"comments":2,"downloads":16,"views":155,"shares":14,"sets":25,"trusted":true,"user":"Music World","userid":958,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14Gg0n_6op1jnDuKQi_tKzM6jmYqFIhAOKicl4oC2","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/c0db27a33bac059311fdbda372a39595.jpeg","created":"1 year ago","tags":null},{"id":621,"kind":"image","title":"Superman Crispy Walls","description":"","review":false,"premium":false,"color":"1c1f4b","size":"1.2 MB","resolution":"1080 X 2160","comment":true,"comments":1,"downloads":40,"views":299,"shares":21,"sets":64,"trusted":true,"user":"Amit yadav","userid":900,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgwmdlsdDVZRkZJT-maWlMEhgZOF232eYwnU07okQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/d92200c2d08c2d050a82a8acff68c899.jpeg","created":"1 year ago","tags":null},{"id":556,"kind":"image","title":"NAGALAND FLAG","description":"NAGALAND FLAG","review":false,"premium":false,"color":"4a7c33","size":"36.87 KB","resolution":"452 X 678","comment":true,"comments":3,"downloads":120,"views":909,"shares":97,"sets":122,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/6422b65234232f8289725297816bcf11.jpeg","created":"1 year ago","tags":null},{"id":555,"kind":"image","title":"FitnessGirl.jpg","description":"","review":false,"premium":false,"color":"caa37b","size":"84.24 KB","resolution":"1060 X 1051","comment":true,"comments":1,"downloads":93,"views":778,"shares":52,"sets":77,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/b966042647b62155d6167e54854e85a7.jpeg","created":"1 year ago","tags":null},{"id":554,"kind":"video","title":"Enjoy","description":"ENJOY","review":false,"premium":false,"color":"464253","size":"791.54 KB","resolution":"307 X 384","comment":true,"comments":2,"downloads":152,"views":881,"shares":83,"sets":179,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4f86ea001331c76904b04d66b182db3b.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4f86ea001331c76904b04d66b182db3b.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/mp4\/804715e391e9c1ea6cd03eeff4025e46.mp4","created":"1 year ago","tags":null},{"id":553,"kind":"image","title":"fucking","description":"sex","review":false,"premium":false,"color":"33001a","size":"192.4 KB","resolution":"1080 X 760","comment":true,"comments":0,"downloads":184,"views":1909,"shares":97,"sets":90,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/4cd6b0f56f6253c3f070293c525d363a.jpeg","created":"1 year ago","tags":null},{"id":549,"kind":"image","title":"b9c1dc6daad1f0362414650b98f488ba.jpg","description":"","review":false,"premium":false,"color":"67635b","size":"60.96 KB","resolution":"564 X 564","comment":true,"comments":0,"downloads":86,"views":775,"shares":69,"sets":118,"trusted":false,"user":"Pubg Gamer","userid":800,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AOh14GgW649CoZrjgQWJy8funiaeUSnmhNFabgVnnk7kvQ","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/341f200d68bca520b5fe846ff9f05bde.jpeg","created":"1 year ago","tags":null},{"id":543,"kind":"image","title":"youtube","description":"jst a test ;p","review":false,"premium":false,"color":"b3b3b3","size":"2.54 KB","resolution":"54 X 65","comment":true,"comments":0,"downloads":40,"views":580,"shares":18,"sets":25,"trusted":true,"user":"Is Boto","userid":696,"userimage":"https:\/\/lh3.googleusercontent.com\/-XdUIqdMkCWA\/AAAAAAAAAAI\/AAAAAAAAAAA\/4252rscbv5M\/photo.jpg","type":"image\/png","extension":"png","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","original":"https:\/\/wallpapers.virmana.com\/uploads\/png\/a2b1af6db244ac806400fee6b00699c9.png","created":"1 year ago","tags":null},{"id":237,"kind":"image","title":"IMG_20191012_212213_627.jpg","description":"","review":false,"premium":false,"color":"576a6a","size":"70.6 KB","resolution":"401 X 401","comment":true,"comments":4,"downloads":733,"views":6458,"shares":292,"sets":608,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/c7c04901281b5c58e0645cc5fc243dc7.jpeg","created":"2 years ago","tags":null},{"id":229,"kind":"image","title":"Shooting stars11472_rectangle.jpg","description":"","review":false,"premium":false,"color":"302fb3","size":"113.7 KB","resolution":"1440 X 2560","comment":true,"comments":4,"downloads":1953,"views":9169,"shares":612,"sets":1461,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/29dd34dc00f9926566769d8a625e62e7.jpeg","created":"2 years ago","tags":null},{"id":228,"kind":"image","title":"Ramadan_42.jpeg","description":"","review":false,"premium":false,"color":"a19a4b","size":"223.97 KB","resolution":"1440 X 2960","comment":true,"comments":3,"downloads":167,"views":1499,"shares":69,"sets":119,"trusted":true,"user":"Сергей Керро","userid":289,"userimage":"https:\/\/lh3.googleusercontent.com\/a-\/AAuE7mBlNhCDCWXbdXykxxfbNiPkfHeZfReX67ItX2qLYg","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/1bc8fea953a2bd114ede402415e1b52e.jpeg","created":"2 years ago","tags":null},{"id":150,"kind":"video","title":"fortlives808","description":"","review":false,"premium":false,"color":"c0a59d","size":"1.95 MB","resolution":"236 X 512","comment":true,"comments":4,"downloads":705,"views":6298,"shares":306,"sets":905,"trusted":true,"user":"Live Wallpapers","userid":143,"userimage":"https:\/\/lh3.googleusercontent.com\/-XdUIqdMkCWA\/AAAAAAAAAAI\/AAAAAAAAAAA\/4252rscbv5M\/photo.jpg","type":"video\/mp4","extension":"mp4","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/4ac1c30dc5b935e59f14103559399219.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/4ac1c30dc5b935e59f14103559399219.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/mp4\/b9f923aef6bfe359ddb7879c4ec50e4c.mp4","created":"2 years ago","tags":null},{"id":63,"kind":"image","title":"aerial shot bird","description":null,"review":false,"premium":false,"color":"533E21","size":"2.15 MB","resolution":"2432 X 3648","comment":true,"comments":14,"downloads":528,"views":3995,"shares":185,"sets":440,"trusted":false,"user":"Jimes diamo","userid":2,"userimage":"https:\/\/platform-lookaside.fbsbx.com\/platform\/profilepic\/?asid=2474801685872271&height=200&width=200&ext=1573584924&hash=AeRg0TAtNhUyDVoS","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_image_api\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/6906d9f5d42a6c3fe7bd5ce2b23b3de9.jpeg","created":"2 years ago","tags":"nature,aerial,shot,bird"},{"id":60,"kind":"gif","title":"Rudolp Jumping Rope","description":null,"review":false,"premium":false,"color":"B5AD81","size":"82.42 KB","resolution":"800 X 600","comment":true,"comments":5,"downloads":254,"views":2652,"shares":120,"sets":472,"trusted":false,"user":"Jimes diamo","userid":2,"userimage":"https:\/\/platform-lookaside.fbsbx.com\/platform\/profilepic\/?asid=2474801685872271&height=200&width=200&ext=1573584924&hash=AeRg0TAtNhUyDVoS","type":"image\/gif","extension":"gif","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","original":"https:\/\/wallpapers.virmana.com\/uploads\/gif\/6068090a2d5c61e61e20bb35de49a6bd.gif","created":"2 years ago","tags":null},{"id":58,"kind":"image","title":"Car night","description":null,"review":false,"premium":false,"color":"9B7663","size":"4.6 MB","resolution":"1960 X 4032","comment":true,"comments":8,"downloads":1622,"views":10038,"shares":438,"sets":1208,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/aa4c8a1e69b4fd32bb0795f409d3bb43.jpeg","created":"2 years ago","tags":"car,night"},{"id":57,"kind":"image","title":"Colored wall","description":null,"review":false,"premium":false,"color":"1e2057","size":"919.59 KB","resolution":"1555 X 1377","comment":true,"comments":3,"downloads":938,"views":5386,"shares":349,"sets":708,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_image_api\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/1ff375a601e02d03f00150bd2f87c36c.jpeg","created":"2 years ago","tags":"color"},{"id":56,"kind":"video","title":"Dark and rainy","description":null,"review":false,"premium":false,"color":"634769","size":"9.91 MB","resolution":"1080 X 1920","comment":true,"comments":1,"downloads":662,"views":3873,"shares":231,"sets":841,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"video\/quicktime","extension":"qt","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/d6f15a4a3c8c838c403d12f7823ce28f.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/d6f15a4a3c8c838c403d12f7823ce28f.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/qt\/2f9fc85a532eb2c07e26027dff37821f.qt","created":"2 years ago","tags":"dark,rainy"},{"id":55,"kind":"gif","title":"China street","description":null,"review":false,"premium":false,"color":"97bcbc","size":"441.04 KB","resolution":"864 X 1536","comment":true,"comments":1,"downloads":308,"views":2603,"shares":103,"sets":327,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/gif","extension":"gif","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","original":"https:\/\/wallpapers.virmana.com\/uploads\/gif\/1154bacc9e1b69462ca3ff32ca54f763.gif","created":"2 years ago","tags":"street,china"},{"id":53,"kind":"image","title":"Galaxy S10","description":null,"review":false,"premium":false,"color":"A1B566","size":"226.2 KB","resolution":"3040 X 3040","comment":true,"comments":2,"downloads":473,"views":3287,"shares":144,"sets":388,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/273d47f7292ce5434c4df9e5f98c443d.jpeg","created":"2 years ago","tags":"samsung,galaxy,s10"},{"id":52,"kind":"image","title":"Galaxy S10","description":null,"review":false,"premium":false,"color":"371676","size":"123.96 KB","resolution":"3040 X 3040","comment":true,"comments":0,"downloads":357,"views":2241,"shares":130,"sets":298,"trusted":false,"user":"Virmana Inc","userid":1,"userimage":"https:\/\/i0.wp.com\/zblogged.com\/wp-content\/uploads\/2019\/02\/FakeDP.jpeg?resize=567%2C580&ssl=1","type":"image\/jpeg","extension":"jpeg","thumbnail":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/wallpaper_thumb_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","image":"https:\/\/wallpapers.virmana.com\/media\/cache\/resolve\/wallpaper_image_api\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","original":"https:\/\/wallpapers.virmana.com\/uploads\/jpeg\/ede513d478e18c5634da67e7d1347e53.jpeg","created":"2 years ago","tags":"samsung,galaxy,s10"}]}';
//         dd(json_decode($a,true));
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
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy($order, 'desc')
                ->paginate($page_limit)
//                ->limit($page_limit)
//                ->offset($limit)
//                ->get()
                ->toArray();

        } else {
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->orderBy($order, 'desc')
                ->paginate($page_limit)
//                ->limit($page_limit)
//                ->offset($limit)
//                ->get()
                ->toArray();

        }

        $data_arr = $this->getWallpaper($wallpaper);
        return json_encode($data_arr,JSON_UNESCAPED_UNICODE);

//
//
//
////        $endpoint = "https://wallpapers.virmana.com/api/wallpaper/all/views/0/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $endpoint = "https://wallpapers.virmana.com/api/first/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $client = new \GuzzleHttp\Client();
//
//
//        $response = Http::get( $endpoint);
//
//        dd($response->json());
//
////        $data_arr = $this->categoriesArray($category);
////        $a = '[{"id":1,"title":"Animals","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/612402c58ac099ef747fe9a402988a47.jpeg"},{"id":2,"title":"Amoled","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/82a7d40f8b5c8d10072a1ddcb00e2b8f.jpeg"},{"id":3,"title":"Cars","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/44fbbbc0c2a1aa8242d723661c00be07.jpeg"},{"id":4,"title":"Gaming","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/4a2da45b9f19c62709115ece8aacc9a0.png"},{"id":5,"title":"Food","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/879b8829ecf7076e2472926cb6a6a26a.jpeg"},{"id":6,"title":"Anime","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/54c869eb9b6baf68c71f68da57712bc5.jpeg"},{"id":7,"title":"Nature","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/420d8e47008b3d2157bc1f7a7ace6e77.jpeg"},{"id":8,"title":"Minimal","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/80191c673eb2e1fbd28bc6539f4d7d1a.jpeg"},{"id":9,"title":"Space","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/6b9a6783306fba49fd6f4a4ef6cd7fa5.jpeg"},{"id":10,"title":"Sport","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b4f96bb607e3d3a9ad77884f5937883f.jpeg"}]';
////        dd($data_arr,json_decode($a,true));
//        return json_encode($data_arr);

    }

    public function wallpapersRandom($page){
        $page_limit = 10;
        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->inRandomOrder()
                ->paginate($page_limit)
//                ->limit($page_limit)
//                ->offset($limit)
//                ->get()
                ->toArray();

        } else {
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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

//
//
//
////        $endpoint = "https://wallpapers.virmana.com/api/wallpaper/all/views/0/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $endpoint = "https://wallpapers.virmana.com/api/first/4F5A9C3D9A86FA54EACEDDD635185/16edd7cf-2525-485e-b11a-3dd35f382457/";
//        $client = new \GuzzleHttp\Client();
//
//
//        $response = Http::get( $endpoint);
//
//        dd($response->json());
//
////        $data_arr = $this->categoriesArray($category);
////        $a = '[{"id":1,"title":"Animals","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/612402c58ac099ef747fe9a402988a47.jpeg"},{"id":2,"title":"Amoled","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/82a7d40f8b5c8d10072a1ddcb00e2b8f.jpeg"},{"id":3,"title":"Cars","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/44fbbbc0c2a1aa8242d723661c00be07.jpeg"},{"id":4,"title":"Gaming","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/png\/4a2da45b9f19c62709115ece8aacc9a0.png"},{"id":5,"title":"Food","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/879b8829ecf7076e2472926cb6a6a26a.jpeg"},{"id":6,"title":"Anime","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/54c869eb9b6baf68c71f68da57712bc5.jpeg"},{"id":7,"title":"Nature","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/420d8e47008b3d2157bc1f7a7ace6e77.jpeg"},{"id":8,"title":"Minimal","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/80191c673eb2e1fbd28bc6539f4d7d1a.jpeg"},{"id":9,"title":"Space","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/6b9a6783306fba49fd6f4a4ef6cd7fa5.jpeg"},{"id":10,"title":"Sport","image":"https:\/\/wallpapers.virmana.com\/uploads\/cache\/category_thumb_api\/uploads\/jpeg\/b4f96bb607e3d3a9ad77884f5937883f.jpeg"}]';
////        dd($data_arr,json_decode($a,true));
//        return json_encode($data_arr);

    }

    public function wallpapersByCategory($page,$category){
        $page_limit = 10;
        $limit= 0 * $page_limit;

        $domain=$_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {


            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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


            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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
            $wallpaper = Wallpapers::where('image_extension', '<>', 'image/gif')->with('category')->whereHas('category', function ($q) use ($domain) {
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
            $data_arr['kind'] = 'image';
            $data_arr['title'] = $item['name'];
            $data_arr['description'] = $item['name'];
            $data_arr['category'] = $item['category']['category_name'];
            $data_arr['color'] =  substr(md5(rand()), 0, 6);;

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

    public function packsArray($data){
        $jsonObj= [];
//        for ($i = 1; $i < 6; $i++){
//        $jsonObj= [];
        foreach ($data as $item){
            $data_arr['id'] = $item->id;
            $data_arr['title'] = $item->category_name;
            foreach ($item->wallpaper->take(5) as $value){
                $data_arr['images'][] = asset('storage/wallpapers/thumbnail/'.$value->thumbnail_image);
            }
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }

    public function slidesArray($data){

        $jsonObj= [];
        foreach ($data as $item){
            $data_arr['id'] = $item['id'];
            $data_arr['title'] = base64_encode($item['category_name']);
            $data_arr['type'] = "1";
            $data_arr['image'] = asset('storage/categories/'.$item['image']);
            array_push($jsonObj,$data_arr);
        }
        return $jsonObj;
    }
}
