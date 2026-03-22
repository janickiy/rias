<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class GazGroup extends Model
{
    protected $table = 'gaz_group';

    protected $fillable = [
        'name',
        'name_ru'
    ];

    /**
     * @return HasManyThrough
     */
    public function gaz(): HasManyThrough
    {
        return $this->hasManyThrough(Gaz::class, GazToGroup::class,'gaz_group_id','id','id','gaz_id');
    }

    /**
     * @return mixed
     */
    public static function getOption()
    {
        return self::orderBy('name_ru')->get()->pluck('name_ru', 'id');
    }

    /**
     * @return void
     */
    public function remove()
    {
        $this->gaz()->delete();

        GazToGroup::where('gaz_group_id', $this->id)->delete();

        $this->delete();
    }

}
