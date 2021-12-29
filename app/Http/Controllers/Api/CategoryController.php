<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\WallpaperResource;
use App\Models\BlockIp;
use App\Http\Controllers\Controller;
use App\Models\CategoryManage;
use App\Models\LoadFeature;


class CategoryController extends Controller
{
    public function index()
    {
        if(checkBlockIp()){
            $data = CategoryManage::where('checked_ip',1)->inRandomOrder()->get();
            return CategoryResource::collection($data);
        } else{
            $data = CategoryManage::has('wallpaper')->where('checked_ip',0)->get();
            return CategoryResource::collection($data);
        }
    }
    public function getWallpapers($id)
    {
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
