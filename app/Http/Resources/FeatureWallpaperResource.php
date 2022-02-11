<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureWallpaperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        foreach ($this->wallpaper->take(1) as $item){
            return [
                'categories' =>
//                    CategoryResource::collection($item->category),
                    new CategoryResource($item->category),
                'id' => $item->id,
                'name' => $item->name,
                'thumbnail_image' => asset('storage/wallpapers/thumbnail/'.$item->thumbnail_image),
                'detail_image' => asset('storage/wallpapers/detail/' . $item->image),
                'download_image' => asset('storage/wallpapers/download/' . $item->origin_image),
                'like_count' => $item->like_count,
                'views' => $item->view_count,
                'feature' => $item->feature,
                'created_at' => $item->created_at->format('d/m/Y'),
            ];
        }
    }
}



