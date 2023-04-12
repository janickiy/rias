<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaz extends Model
{
    const CONVERT_FROM_PPM = 'covertFromPpm';
    const CONVERT_FROM_MG = 'covertFromMg';
    const CONVERT_FROM_OBD = 'covertFromObd';
    const CONVERT_FROM_NKPR = 'covertFromNkpr';

    public $ppm;

    public $mg;

    public $obd;

    protected $table = 'gaz';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'weight',
        'chemical_formula',
        'chemical_formula_html',
    ];

    public static function getTypes()
    {
        return [
            self::CONVERT_FROM_PPM => 'ppm',
            self::CONVERT_FROM_MG => 'мг/м3',
            self::CONVERT_FROM_OBD => '% об. д.',
            self::CONVERT_FROM_NKPR => '% НКПР',
        ];
    }

    /**
     * @return mixed
     */
    public function getGaz()
    {
        return self::find($this->id);
    }

    /**
     * @param $type
     * @param $value
     * @return void
     */
    public function convert($type, $value)
    {
        if (!$gaz = $this->getGaz()) {
            return;
        }

        switch ($type) {
            case self::CONVERT_FROM_PPM:
                return  $gaz->covertFromPpm($value);
            case self::CONVERT_FROM_MG:
                return $gaz->covertFromMg($value);
            case self::CONVERT_FROM_OBD:
                return  $gaz->covertFromObd($value);
            case self::CONVERT_FROM_NKPR:
                return  $gaz->covertFromNkpr($value);
        }

    }

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
    public function covertFromMg($value): array
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
    public function covertFromPpm($value): array
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
    public function covertFromObd($value): array
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
    public static function covertFromNkpr($value): array
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

    /**
     * @return void
     */
    public function scopeRemove()
    {
        GazToGroup::where('gaz_id', $this->id)->delete();
        $this->delete();
    }

    /**
     * @return mixed
     */
    public static function getOption()
    {
        return self::orderBy('title')->get()->pluck('title', 'id');
    }

}
