<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WallpaperResource extends JsonResource
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
            'categories' =>
//                CategoryResource::collection($this->category),
                array(new CategoryResource($this->category)),
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail_image' => asset('storage/wallpapers/thumbnail/'.$this->thumbnail_image),
            'detail_image' => asset('storage/wallpapers/detail/' . $this->image),
            'download_image' => asset('storage/wallpapers/download/' . $this->origin_image),
            'like_count' => $this->like_count,
            'views' => $this->view_count,
            'feature' => $this->feature,
            'created_at' => $this->created_at->format('d/m/Y'),
        ];
    }
}
