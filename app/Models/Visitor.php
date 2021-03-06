<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;
    protected $fillable = [
        'device_id'
    ];

    public function wallpapers(){
        return $this->belongsToMany(Wallpapers::class,'visitor_favorites','visitor_id','wallpaper_id');
    }

}
