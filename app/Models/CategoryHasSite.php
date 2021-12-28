<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryHasSite extends Model
{
    use HasFactory;
    protected $table ='tbl_category_has_site';
    protected $fillable = [];
}
