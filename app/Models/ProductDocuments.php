<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDocuments extends Model
{
    protected $table = 'product_documents';

    protected $primaryKey = 'id';

    protected $fillable = [
        'path',
        'description',
        'product_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
