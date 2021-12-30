<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\WallpaperResource;
use App\Models\BlockIp;
use App\Http\Controllers\Controller;
use App\Models\CategoryManage;
use App\Models\LoadFeature;
use App\Models\SiteManage;


class CategoryController extends Controller
{
    public function index()
    {
        $domain=$_SERVER['SERVER_NAME'];
        if(checkBlockIp()){
            $data = SiteManage::with('category')
                ->leftJoin('tbl_category_has_site', 'tbl_category_has_site.site_id', '=', 'tbl_site_manages.id')
                ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'tbl_category_has_site.category_id')
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',1)
                ->inRandomOrder()
                ->get();
            return CategoryResource::collection($data);

        } else{
            $data = SiteManage::with('category')
                ->leftJoin('tbl_category_has_site', 'tbl_category_has_site.site_id', '=', 'tbl_site_manages.id')
                ->leftJoin('tbl_category_manages', 'tbl_category_manages.id', '=', 'tbl_category_has_site.category_id')
                ->where('site_name',$domain)
                ->where('tbl_category_manages.checked_ip',0)
                ->get();
            return CategoryResource::collection($data);
        }
    }
    public function getWallpapers($id)
    {
        $domain=$_SERVER['SERVER_NAME'];
        try{
            $wallpapers = CategoryManage::findOrFail($id)
                ->wallpaper()
                ->orderBy('like_count', 'desc')
                ->paginate(70);
            CategoryManage::findOrFail($id)->increment('view_count');
            return WallpaperResource::collection($wallpapers);
        }catch (\Exception $e){
            return response()->json(['warning' => ['This Category is not exist']], 200);
        }

    }
}
