<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photoalbums extends Model
{

    protected $table = 'photoalbums';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Images::class,'photoalbum_id','id');
    }

}
