<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockIP extends Model
{
    use HasFactory;
    protected $table ='block_i_p_s';


    public function sites()
    {
        return $this->belongsToMany(SiteManage::class, 'tbl_site_has_block_ip', 'blockIps_id', 'sites_id');
    }
}
