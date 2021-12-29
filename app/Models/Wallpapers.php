<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallpapers extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsToMany('App\Models\CategoryManage', 'tbl_category_has_wallpaper', 'wallpaper_id', 'category_id');
    }

    public function visitors(){
        return $this->belongsToMany(Visitor::class,'visitor_favorites');
    }

}
