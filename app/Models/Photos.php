<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{

    protected $table = 'photos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'photoalbum_id',
        'small_photo',
        'photo',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photoalbum()
    {
        return $this->belongsTo(Photoalbums::class, 'id', 'photoalbum_id');
    }

}
