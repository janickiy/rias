<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GazToGroup extends Model
{

    protected $table = 'gaz_to_group';

    public $timestamps = false;

    protected $fillable = [
        'gaz_id',
        'gaz_group_id',
    ];

    protected $primaryKey = ['gaz_id','gaz_group_id'];

    /**
     * Indicates if the IDs are auto-incrementing
     *
     * @var bool
     */
    public $incrementing = false;

}
