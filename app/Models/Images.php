<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Images extends Model
{

    protected $table = 'images';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'photoalbum_id',
        'thumbnail',
        'origin',
    ];

    /**
     * @return mixed
     */
    public function getThumbnailUrl()
    {
        return Storage::disk('public')->url('app/public/images/' . $this->thumbnail);
    }

    /**
     * @return mixed
     */
    public function getOriginUrl()
    {
        return Storage::disk('public')->url('app/public/images/' . $this->origin);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photoalbum()
    {
        return $this->belongsTo(Photoalbums::class,'photoalbum_id','id');
    }

}
