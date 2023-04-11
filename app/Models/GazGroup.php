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
    public function scopeRemove()
    {
        $this->gaz()->delete();

        GazToGroup::where('gaz_group_id', $this->id)->delete();

        $this->delete();
    }

}
