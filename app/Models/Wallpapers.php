<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpapers extends Model
{
    use HasFactory;
    protected $fillable =[
        'cate_id','name','origin_image','image','thumbnail_image','view_count','feature','like_count','hash_file','image_extension'
    ];

    public function category()
    {
        return $this->belongsToMany('App\Models\CategoryManage', 'tbl_category_has_wallpaper', 'wallpaper_id', 'category_id');
    }

//    public function category()
//    {
//        return $this->belongsTo('App\Models\CategoryManage','cate_id');
//    }

    public function visitors(){
        return $this->belongsToMany(Visitor::class,'visitor_favorites');
    }





}
