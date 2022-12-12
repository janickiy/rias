<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{

    protected $table = 'catalog';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
    ];

}
