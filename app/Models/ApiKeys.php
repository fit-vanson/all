<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiKeys extends Model
{
    use HasFactory,
        SoftDeletes;


    public function sites()
    {
        return $this->hasMany(SiteManage::class, 'apikey_id');
    }
}
