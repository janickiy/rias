<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\VideoHelper;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVideos extends Model
{
    protected $table = 'product_videos';

    protected $fillable = [
        'provider',
        'video',
        'product_id'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    /**
     * @return string
     */
    public function getThumb(): string
    {
        return VideoHelper::getThumb($this->provider,$this->video);
    }

    /**
     * @return string
     */
    public function getVideoUrl(): string
    {
        return VideoHelper::getVideoUrl($this->provider,$this->video);
    }
}
