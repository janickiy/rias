<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Catalog extends Model
{

    protected $table = 'catalog';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image',
        'slug',
        'seo_h1',
        'seo_url_canonical',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'catalog_id', 'id');
    }

    /**
     * @return mixed
     */
    public static function getOption()
    {
        return self::orderBy('name')->get()->pluck('name', 'id');
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return Storage::disk('public')->url('app/public/catalog/' . $this->image);
    }

}
