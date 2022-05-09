<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WallpaperResource_V1 extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $categories = (new CategoryResource_V1($this->category));
        return [
            'image_id' => $this->id,
            'image_name' => $this->name,
            'image_upload' => $this->image,
            'image_url' => $this->image,
            'views' => $this->view_count,
            'downloads' => $this->like_count,
            'featured' => $this->feature == 0 ? 'yes':'no',
            'tags' => $categories->category_name,
            'category_id' => $categories->id,
            'category_name' => $categories->category_name,
            'last_update' => $this->updated_at,
        ];
    }
}
