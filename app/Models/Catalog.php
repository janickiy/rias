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
        'description',
        'scope',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'image',
        'slug',
        'seo_h1',
        'seo_url_canonical',
        'image_title',
        'image_alt'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Products::class, 'catalog_id', 'id');
    }

    /**
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getProductsList(int $limit = null)
    {
        return $limit ? $this->hasMany(Products::class, 'catalog_id', 'id')->limit(5) : $this->hasMany(Products::class, 'catalog_id', 'id')->limit($limit);
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

    /**
     * @return void
     */
    public function scopeRemove()
    {
        if (Storage::disk('public')->exists('catalog/' . $this->image) === true) Storage::disk('public')->delete('catalog/' . $this->image);

        foreach ($this->products as $product) {
            $product->remove();
        }

        $this->delete();
    }

}
