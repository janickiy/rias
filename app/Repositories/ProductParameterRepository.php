<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\ProductParameterData;
use App\Models\ProductParameters;
use Illuminate\Database\Eloquent\Collection;

class ProductParameterRepository
{
    public function find(int $id): ?ProductParameters
    {
        return ProductParameters::find($id);
    }

    public function findOrFail(int $id): ProductParameters
    {
        return ProductParameters::findOrFail($id);
    }

    public function getByProductId(int $productId): Collection
    {
        return ProductParameters::query()
            ->where('product_id', $productId)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }

    public function create(ProductParameterData $data): ProductParameters
    {
        $parameter = new ProductParameters();
        $parameter->fill($this->prepareCreateAttributes($data));
        $parameter->save();

        return $parameter;
    }

    public function update(ProductParameters $parameter, ProductParameterData $data): bool
    {
        return $parameter->update($this->prepareUpdateAttributes($data));
    }

    public function delete(ProductParameters $parameter): bool
    {
        return (bool) $parameter->delete();
    }

    public function deleteById(int $id): bool
    {
        $parameter = $this->find($id);

        if ($parameter === null) {
            return false;
        }

        return $this->delete($parameter);
    }

    private function prepareCreateAttributes(ProductParameterData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(ProductParameterData $data): array
    {
        return $data->toArray();
    }
}
