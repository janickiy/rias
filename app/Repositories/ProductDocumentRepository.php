<?php

namespace App\Repositories;

use App\DTO\Admin\ProductDocumentData;
use App\Models\ProductDocuments;

class ProductDocumentRepository
{
    public function create(ProductDocumentData $data): ProductDocuments
    {
        return ProductDocuments::create($data->toArray());
    }

    public function update(ProductDocuments $document, ProductDocumentData $data): ProductDocuments
    {
        $document->update($data->toArray());

        return $document->refresh();
    }

    public function delete(ProductDocuments $document): void
    {
        $document->delete();
    }
}
