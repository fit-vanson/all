<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiV4Controller extends Controller
{
    public function index(){
        dd(1);
    }

    public function randomWallpaper()
    {
        $domain = $_SERVER['SERVER_NAME'];
        if (checkBlockIp()) {
            $wallpapers = Wallpapers::whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 1)
                    ->select('tbl_category_manages.*');
            })
                ->inRandomOrder()
                ->limit(10)
                ->get();

        } else {
            $wallpapers = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
                $q->leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                    ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                    ->where('site_name', $domain)
                    ->where('tbl_category_manages.checked_ip', 0)
                    ->select('tbl_category_manages.*');
            })
                ->inRandomOrder()
                ->limit(10)
                ->get();

        }
        $jsonObj = [];
        foreach ($wallpapers as $wallpaper){
            $data_arr = $this->getWallpaper($wallpaper);
            array_push($jsonObj,$data_arr);
        }

//        $endpoint = "https://api.unsplash.com/photos/random?client_id=g7pCnQVE4Y2DxlMqvwt2AAal-HzvbZdMsZRNqd8c9hU&count=5";
//        $response = Http::get( $endpoint);
//        dd($response->json(),$jsonObj);
//        $data_arr = $response->json();


        return $jsonObj;

    }
    public  function Wallpaper($id){
        $wallpaper = Wallpapers::find($id);
        $data_arr = $this->getWallpaper($wallpaper);
        return $data_arr;
    }

    private  function getWallpaper($data){

            $path = storage_path('app/public/wallpapers/download/'.$data['origin_image']);
            $image = $size = '';
            if (file_exists($path)){
                $image = getimagesize($path);
//                $size = $this->filesize_formatted($path);
            }


            $data_arr['id'] = $data['id'];
            $data_arr['created_at'] = $data['created_at']->toDateString();
            $data_arr['width'] = $image ?  $image[0] : '';
            $data_arr['height'] = $image ?  $image[1] : '';

            $data_arr['urls']['raw'] = asset('storage/wallpapers/download/' . $data['origin_image']);
            $data_arr['urls']['full'] = asset('storage/wallpapers/download/' . $data['origin_image']);
            $data_arr['urls']['regular'] = asset('storage/wallpapers/detail/' . $data['image']);
            $data_arr['urls']['small'] = asset('storage/wallpapers/thumbnail/' . $data['thumbnail_image']);
            $data_arr['urls']['thumb'] = asset('storage/wallpapers/thumbnail/' . $data['thumbnail_image']);
            $data_arr['urls']['small_s3'] = asset('storage/wallpapers/thumbnail/' . $data['thumbnail_image']);

            $data_arr['links']['self'] = route('v4.wallpaper',['id'=>$data['id']]);
            $data_arr['links']['html'] = asset('storage/wallpapers/download/' . $data['origin_image']);
            $data_arr['links']['download'] = asset('storage/wallpapers/download/' . $data['origin_image']);
            $data_arr['links']['download_location'] =  route('v4.wallpaper',['id'=>$data['id']]);

            $data_arr['likes'] =  $data['like_count'];
            $data_arr['views'] =  $data['view_count'];
            $data_arr['downloads'] =  rand(300,1000);

        return $data_arr;
    }


}
