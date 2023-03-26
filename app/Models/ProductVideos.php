<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideos extends Model
{
    protected $table = 'product_videos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'provider',
        'video',
        'product_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
