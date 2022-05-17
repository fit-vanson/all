<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WallpaperResource_V2 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'num' => 125,
            'id' => $this->id,
            'cat_id' => $this->cate_id,
            'wallpaper_type' => 'Portrait',
            'wallpaper_image' =>  asset('storage/wallpapers/download/' . $this->origin_image),
            'wallpaper_image_thumb' => asset('storage/wallpapers/thumbnail/'.$this->thumbnail_image),
            'total_views' => $this->view_count,
            'total_rate' => $this->view_count,
            'rate_avg' => rand(4,5),
            'total_download' => $this->like_count,


            'is_favorite' => 'false',

            'wall_tags' => "Abstract,Portrait",
            'wall_colors' => 2,

            'cid' => $this->cate_id,
            'category_name' => $this->category->category_name,
            'category_image' => asset('storage/categories/'.$this->category->image),
            'category_image_thumb' => asset('storage/categories/'.$this->category->image),


        ];
    }
}
