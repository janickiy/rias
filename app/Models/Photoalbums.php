<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photoalbums extends Model
{

    protected $table = 'photoalbums';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photos::class,'photoalbum_id','id');
    }

}
