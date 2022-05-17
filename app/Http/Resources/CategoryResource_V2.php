<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource_V2 extends JsonResource
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
            'cid' => $this->id,
            'category_name' => $this->category_name,
            'category_image' => $this->site_image ? asset('storage/categories/'.$this->site_image) : asset('storage/categories/'.$this->image),
            'category_image_thumb' => $this->site_image ? asset('storage/categories/'.$this->site_image) : asset('storage/categories/'.$this->image),
            'category_total_wall' => $this->wallpaper_count,
        ];
    }
}
