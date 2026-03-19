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
        $relation = $this->hasMany(Products::class, 'catalog_id', 'id');

        return $limit ? $relation->limit($limit) : $relation;
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
        return Storage::disk('public')->url('catalog/' . $this->image);
    }

    /**
     * @return void
     */
    public function remove()
    {
        if (Storage::disk('public')->exists('catalog/' . $this->image) === true) Storage::disk('public')->delete('catalog/' . $this->image);

        foreach ($this->products as $product) {
            $product->remove();
        }

        $this->delete();
    }

}
