<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListIp extends Model
{
    use HasFactory;
    protected $fillable = [
        'ip_address','id_site'
    ];
    protected $table ='list_ips';
    public function site()
    {
        return $this->belongsTo(SiteManage::class);
    }
}
