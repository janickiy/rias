<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\ProductData;
use App\Models\Products;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function find(int $id): ?Products
    {
        return Products::find($id);
    }

    public function findOrFail(int $id): Products
    {
        return Products::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Products::query()
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();
    }

    public function create(ProductData $data): Products
    {
        $product = new Products();
        $product->fill($this->prepareCreateAttributes($data));
        $product->save();

        return $product;
    }

    public function update(Products $product, ProductData $data): bool
    {
        return $product->update($this->prepareUpdateAttributes($data));
    }

    public function delete(Products $product): bool
    {
        return (bool) $product->delete();
    }

    private function prepareCreateAttributes(ProductData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(ProductData $data): array
    {
        return $data->toArray();
    }
}
