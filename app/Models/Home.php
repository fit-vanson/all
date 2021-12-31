<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;
    protected $fillable = [
        'site_id',
        'header_title',
        'header_content',
        'header_image',
        'body_title',
        'body_content',
        'footer_title',
        'footer_content',
    ];

    public function site()
    {
        return $this->belongsTo(SiteManage::class,'site_id');
    }
}
