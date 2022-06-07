<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\WallpaperResource;
use App\Models\BlockIp;
use App\Http\Controllers\Controller;
use App\Models\CategoryManage;
use App\Models\SiteManage;


class CategoryController extends Controller
{
    public function index()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',1)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->get();
            return CategoryResource::collection($data);
        } else{
            $data = CategoryManage::
            leftJoin('tbl_category_has_site', 'tbl_category_has_site.category_id', '=', 'tbl_category_manages.id')
                ->leftJoin('tbl_site_manages', 'tbl_site_manages.id', '=', 'tbl_category_has_site.site_id')
                ->has('wallpaper','>',0)
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',0)
                ->select('tbl_category_manages.*','tbl_category_has_site.image as site_image')
                ->get();
            return CategoryResource::collection($data);
        }
    }
    public function getWallpapers($id)
    {
        $domain=$_SERVER['SERVER_NAME'];
        $page_limit = 12;
        $limit=($_GET['page']-1) * $page_limit;
        try{
            $wallpapers = CategoryManage::findOrFail($id)
                ->wallpaper()
                ->where('image_extension', '<>', 'image/gif')
                ->orderBy('like_count', 'desc')
                ->skip($limit)
                ->take($page_limit)
                ->get();

//                ->paginate(10);
            CategoryManage::findOrFail($id)->increment('view_count');
            return WallpaperResource::collection($wallpapers);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
}
