<?php

namespace App\Repositories;

use App\DTO\Admin\ProductData;
use App\Models\Products;

class ProductRepository
{
    public function create(ProductData $data): Products
    {
        return Products::create($data->toArray());
    }

    public function update(Products $product, ProductData $data): Products
    {
        $product->update($data->toArray());

        return $product->refresh();
    }

    public function delete(Products $product): void
    {
        $product->remove();
    }
}
