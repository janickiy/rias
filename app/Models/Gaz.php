<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaz extends Model
{

    protected $table = 'gaz';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'weight',
        'chemical_formula',
        'chemical_formula_html',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function group()
    {
        return $this->hasManyThrough(GazGroup::class, GazToGroup::class,'gaz_id','id','id','gaz_group_id');
    }

    /**
     * @param $value
     * @return array
     */
    public function covertFromMg($value)
    {
        $result = [];
        $result['mg'] = $value;
        $result['ppm'] = (8312.6 * $value * 293.15) / ($this->weight * 101325);
        $result['obd'] = $result['ppm'] / 10000;
        $result['nkpr'] = ($result['obd'] / 4) * 100;

        return $result;
    }

    /**
     * @param $value
     * @return array
     */
    public function covertFromPpm($value)
    {
        $result = [];
        $result['mg'] = 0.12 * ($value / 1000) * $this->weight * 101325 / 293.15;
        $result['ppm'] = $value;
        $result['obd'] = $value / 10000;
        $result['nkpr'] = ($result['obd'] / 4) * 100;

        return $result;
    }

    /**
     *
     * @param $value
     * @return array
     */
    public function covertFromObd($value)
    {
        $result = [];
        $result['mg'] = 0.12 * ($value / 0.1) * $this->weight * 101325 / 293.15;
        $result['ppm'] = $value * 10000;
        $result['obd'] = $value;
        $result['nkpr'] = ($value / 4) * 100;

        return $result;
    }

    /**
     * @param $value
     * @return array
     */
    public static function covertFromNkpr($value)
    {
        $result = [];
        $result['mg'] = 0;//0.12 * ($value / 0.1) * $gazWeight * 101325 / 293.15;
        $result['ppm'] = 0;//$value * 10000;
        $result['pbd'] = 0;//($value / 4) * 100;

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function groups()
    {
        return $this->hasManyThrough(GazGroup::class, GazToGroup::class,'gaz_id','id','id','gaz_group_id');
    }

    public function scopeRemove()
    {
        GazToGroup::where('gaz_id', $this->id)->delete();
        $this->delete();
    }

}
