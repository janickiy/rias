<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GazGroup extends Model
{
    protected $table = 'gaz_group';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'name_ru'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function gaz()
    {
        return $this->hasManyThrough(Gaz::class, GazToGroup::class,'gaz_group_id','id','id','gaz_id');
    }


}
