<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\ProductDocumentData;
use App\Models\ProductDocuments;
use Illuminate\Database\Eloquent\Collection;

class ProductDocumentRepository
{
    public function find(int $id): ?ProductDocuments
    {
        return ProductDocuments::find($id);
    }

    public function findOrFail(int $id): ProductDocuments
    {
        return ProductDocuments::findOrFail($id);
    }

    public function getByProductId(int $productId): Collection
    {
        return ProductDocuments::query()
            ->where('product_id', $productId)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }

    public function create(ProductDocumentData $data): ProductDocuments
    {
        $document = new ProductDocuments();
        $document->fill($this->prepareCreateAttributes($data));
        $document->save();

        return $document;
    }

    public function update(ProductDocuments $document, ProductDocumentData $data): bool
    {
        return $document->update($this->prepareUpdateAttributes($data));
    }

    public function delete(ProductDocuments $document): bool
    {
        return (bool) $document->delete();
    }

    public function deleteById(int $id): bool
    {
        $document = $this->find($id);

        if ($document === null) {
            return false;
        }

        return $this->delete($document);
    }

    private function prepareCreateAttributes(ProductDocumentData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(ProductDocumentData $data): array
    {
        return $data->toArray();
    }
}
