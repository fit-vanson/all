<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource_V3 extends JsonResource
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
            'id' => $this->id,
            'title' => $this->category_name,
            'view_count'=>$this->view_count,
            'extension'=> 'jpeg',
            'image' => $this->site_image ?  asset('storage/categories/'.$this->site_image) : asset('storage/categories/'.$this->image),
        ];
    }
}
