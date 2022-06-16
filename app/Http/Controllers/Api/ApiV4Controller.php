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
            $wallpaper = Wallpapers::whereHas('category', function ($q) use ($domain) {
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
            $wallpaper = Wallpapers::with('category')->whereHas('category', function ($q) use ($domain) {
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
        $data_arr = $this->getWallpaper($wallpaper);



        $endpoint = "https://api.unsplash.com/photos/random?client_id=g7pCnQVE4Y2DxlMqvwt2AAal-HzvbZdMsZRNqd8c9hU&count=5";
        $response = Http::get( $endpoint);
//        dd($response->json(),1);
//        dd($data_arr);

        $data_arr = $response->json();

        return $data_arr;

    }

    private  function getWallpaper($data){

        $jsonObj = [];
        foreach ($data as $item){

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
}
