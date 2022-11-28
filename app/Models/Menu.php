<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{

    const PER_PAGE = 1000;

    protected $table = 'menu';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'menu_type',
        'item_id',
        'url',
        'status',
        'item_order',
        'parent_id',
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeStatus($query)
    {
        return $query->where('status', 'true');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemurl()
    {
        return $this->belongsTo(Pages::class, 'item_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo($this, 'parent_id', 'id');
    }

}
