<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'image',
        'description',
        'catalog_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
    ];
}
