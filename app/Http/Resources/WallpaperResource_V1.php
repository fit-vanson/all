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
        $path = storage_path('app/public/wallpapers/download/'.@$this->image);
        $image = $size = '';
        if (file_exists($path)){
            $image = getimagesize($path,$info);
            $size = $this->filesize_formatted($path);
        }
        $categories = (new CategoryResource_V1($this->category));
        return [
            'image_id' => $this->id,
            'image_name' => $this->name,
            'image_upload' => $this->image,
            'image_url' => $this->image_url,
            'type' => 'upload',
            'resolution' =>$image ?  $image[0]. ' x '.$image[1]: 'n/a',
            'size' => $size ? $size : 'n/a',
            'mime' => $image ?  $image['mime'] : 'n/a',
            'views' => $this->view_count,
            'downloads' => $this->like_count,
            'featured' => $this->feature == 0 ? 'yes':'no',
            'tags' => $categories->category_name,
            'category_id' => $categories->id,
            'category_name' => $categories->category_name,
            'last_update' => $this->updated_at->format('Y-m-d h:i:s'),
        ];
    }
    function filesize_formatted($path)
    {
        $size = filesize($path);
        $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $power = $size > 0 ? floor(log($size, 1024)) : 0;
        return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

}
