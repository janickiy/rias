<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\VideoHelper;

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

    /**
     * @return string
     */
    public function getThumb()
    {
        return VideoHelper::getThumb($this->provider,$this->video);
    }

    /**
     * @return string
     */
    public function getVideoUrl()
    {
        return VideoHelper::getVideoUrl($this->provider,$this->video);
    }
}
