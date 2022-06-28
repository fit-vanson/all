<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryManage extends Model
{
    use HasFactory;
    protected $table = 'tbl_category_manages';
    protected $fillable = [];


    public function site()
    {
        return $this->belongsToMany('App\Models\SiteManage', 'tbl_category_has_site', 'category_id', 'site_id');
    }

    public function wallpaper()
    {
        return $this->belongsToMany(Wallpapers::class, 'tbl_category_has_wallpaper', 'category_id', 'wallpaper_id');
    }


//    public function wallpaper()
//    {
//        return $this->hasMany(Wallpapers::class,'cate_id');
//    }


    public static function booted()
    {
        static::deleting(function ($category) {
            $walpapers = $category->wallpaper()->get();
//            dd($walpapers->pluck('id')->toArray());
            $sites = $category->site()->get();

            if ($walpapers->isNotEmpty()) {
                $category->wallpaper()->detach();
                $defaultCategory = static::find(1);
                $defaultCategory->wallpaper()->sync($walpapers->pluck('id')->toArray(),false);
            }
            if ($sites->isNotEmpty()) {
                $category->site()->detach();
                $defaultCategory = static::find(1);
                $defaultCategory->site()->sync($sites->pluck('id')->toArray(),false);
            }
        });
    }
}
