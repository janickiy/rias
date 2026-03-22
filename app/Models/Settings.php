<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Settings extends Model
{

    protected $table = 'settings';

    protected $fillable = [
        'key_cd',
        'type',
        'display_value',
        'value'
    ];


    public function setKeyCdAttribute(string $value)
    {
        $this->attributes['key_cd'] = str_replace(' ', '_', strtoupper($value));
    }

    /**
     * @return string
     */
    public function getTypeAttribute() {
        return $this->attributes['type'] = strtoupper($this->attributes['type']);
    }

    /**
     * @return array|string
     */
    public function getValueAttribute(): mixed
    {
        if ($this->attributes['type'] == 'FILE') {
            return Storage::disk('public')->url('settings/' . $this->attributes['value']);
        }

        return $this->attributes['value'];
    }

    public function filePath()
    {
        return $this->attributes['value'];
    }

}
