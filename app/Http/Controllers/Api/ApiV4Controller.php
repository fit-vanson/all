<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallpapers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use kornrunner\Blurhash\Blurhash;
use League\ColorExtractor\Palette;

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
                ->limit($_GET['count'])
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
                ->limit($_GET['count'])
                ->get();

        }
        $jsonObj = [];
        foreach ($wallpapers as $wallpaper){
            $data_arr = $this->getWallpaper($wallpaper);
            array_push($jsonObj,$data_arr);
        }

//        $endpoint = "https://api.unsplash.com/photos/random?client_id=g7pCnQVE4Y2DxlMqvwt2AAal-HzvbZdMsZRNqd8c9hU&count=5";
//        $response = Http::get( $endpoint);
//        $data_arr = $response->json();
//        dd($data_arr);


        return $jsonObj;

    }
    public  function Wallpaper($id){
        $wallpaper = Wallpapers::find($id);
        $data_arr = $this->getWallpaper($wallpaper);
        return $data_arr;
    }

    private  function getWallpaper($data){
        $width = $height = $blurhash = $index = null;

            $path = storage_path('app/public/wallpapers/download/'.$data['origin_image']);

            if (file_exists($path)){
                $image = imagecreatefromstring(file_get_contents($path));
                $width = imagesx($image);
                $height = imagesy($image);

                $r = $g = $b = 0;

                $pixels = [];
                for ($y = 0; $y < $height; ++$y) {
                    $row = [];
                    for ($x = 0; $x < 100; ++$x) {

                        $rgb = imagecolorat($image, $x, $y);
                        $r += $rgb >> 16;
                        $g += $rgb >> 8 & 255;
                        $b += $rgb & 255;


                        $index = imagecolorat($image, $x, $y);
                        $colors = imagecolorsforindex($image, $index);

                        $row[] = [$colors['red'], $colors['green'], $colors['blue']];
                    }
                    $pixels[] = $row;
                }

                $pxls = $width * $height;
                $r = dechex(round($r / $pxls));
                $g = dechex(round($g / $pxls));
                $b = dechex(round($b / $pxls));
                if(strlen($r) < 2) {
                    $r = 0 . $r;
                }
                if(strlen($g) < 2) {
                    $g = 0 . $g;
                }
                if(strlen($b) < 2) {
                    $b = 0 . $b;
                }

                $components_x = 4;
                $components_y = 3;
                $blurhash = Blurhash::encode($pixels, $components_x, $components_y);

                $index = '#'.$r.$g.$b;
            }

        $endpoint = "https://api.unsplash.com/photos/muX4vR4pEyc?client_id=g7pCnQVE4Y2DxlMqvwt2AAal-HzvbZdMsZRNqd8c9hU";
        $response = Http::get( $endpoint);
        $dataA = $response->json();
        return $dataA;
//        dd($dataA);


        $data_arr['id'] = $data['id'];
        $data_arr['created_at'] = $data['created_at']->toDateString();
        $data_arr['width'] = $width;
        $data_arr['height'] = $height ;
        $data_arr['color'] = $index ;
        $data_arr['blur_hash'] = $blurhash ;
        $data_arr['description'] = null;

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

        $data_arr['user'] =  $dataA[0]['user'];
        $data_arr['exif'] =  $dataA[0]['exif'];
        $data_arr['location'] =  $dataA[0]['location'];






        return $data_arr;
    }




}
