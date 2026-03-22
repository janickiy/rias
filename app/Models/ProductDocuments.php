<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Storage;

class ProductDocuments extends Model
{
    protected $table = 'product_documents';

    protected $fillable = [
        'path',
        'description',
        'product_id'
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }

    /**
     * @return string
     */
    public function getDocument(): string
    {
        return Storage::disk('public')->url('documents/' . $this->path);
    }
}
