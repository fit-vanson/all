<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockIpsHasSite extends Model
{
    use HasFactory;
    protected $table= 'tbl_site_has_block_ip';
    protected $fillable = [];
}
