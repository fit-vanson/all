<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    use HasFactory;

    public function site()
    {
        return $this->belongsTo(SiteManage::class,'site_id');
    }
}
