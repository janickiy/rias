<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\ProductPhotoData;
use App\Models\ProductPhotos;
use Illuminate\Database\Eloquent\Collection;

class ProductPhotoRepository
{
    public function find(int $id): ?ProductPhotos
    {
        return ProductPhotos::find($id);
    }

    public function findOrFail(int $id): ProductPhotos
    {
        return ProductPhotos::with('product')->findOrFail($id);
    }

    public function getByProductId(int $productId): Collection
    {
        return ProductPhotos::query()
            ->where('product_id', $productId)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }

    public function create(ProductPhotoData $data): ProductPhotos
    {
        $photo = new ProductPhotos();
        $photo->fill($this->prepareCreateAttributes($data));
        $photo->save();

        return $photo;
    }

    public function update(ProductPhotos $photo, ProductPhotoData $data): bool
    {
        return $photo->update($this->prepareUpdateAttributes($data));
    }

    public function delete(ProductPhotos $photo): bool
    {
        return (bool) $photo->delete();
    }

    public function deleteById(int $id): bool
    {
        $photo = $this->find($id);

        if ($photo === null) {
            return false;
        }

        return $this->delete($photo);
    }

    private function prepareCreateAttributes(ProductPhotoData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(ProductPhotoData $data): array
    {
        return $data->toArray();
    }
}
