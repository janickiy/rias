<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Storage;

class Products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'catalog_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
        'seo_h1',
        'seo_url_canonical',
        'thumbnail',
        'origin',
        'image_title',
        'image_alt'
    ];

    /**
     * @return BelongsTo
     */
    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_id', 'id');
    }

    /**
     * @return string
     */
    public function getThumbnailUrl(): string
    {
        return Storage::disk('public')->url('products/' . $this->thumbnail);
    }

    /**
     * @return string
     */
    public function getOriginUrl(): string
    {
        return Storage::disk('public')->url('products/' . $this->origin);
    }

    /**
     * @return HasMany
     */
    public function parameters(): HasMany
    {
        return $this->hasMany(ProductParameters::class, 'product_id', 'id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ProductPhotos::class, 'product_id', 'id');
    }


    public function videos(): HasMany
    {
        return $this->hasMany(ProductVideos::class, 'product_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ProductDocuments::class, 'product_id', 'id');
    }


    /**
     * @return void
     */
    public function remove(): void
    {

        if (Storage::disk('public')->exists('products/' . $this->thumbnail) === true) Storage::disk('public')->delete('products/' . $this->thumbnail);
        if (Storage::disk('public')->exists('products/' . $this->origin) === true) Storage::disk('public')->delete('products/' . $this->origin);

        foreach ($this->photos as $photo) {
            if (Storage::disk('public')->exists('images/' . $photo->thumbnail) === true) Storage::disk('public')->delete('images/' . $photo->thumbnail);
            if (Storage::disk('public')->exists('images/' . $photo->origin) === true) Storage::disk('public')->delete('images/' . $photo->origin);
        }

        $this->photos()->delete();

        foreach ($this->documents as $document) {
            if (Storage::disk('public')->exists('documents/' . $document->path) === true) Storage::disk('public')->delete('documents/' . $document->path);
        }

        $this->documents()->delete();
        $this->parameters()->delete();
        $this->videos()->delete();
        $this->delete();
    }

}
