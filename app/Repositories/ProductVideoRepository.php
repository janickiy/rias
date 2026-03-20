<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\ProductVideoData;
use App\Models\ProductVideos;
use Illuminate\Database\Eloquent\Collection;

class ProductVideoRepository
{
    public function find(int $id): ?ProductVideos
    {
        return ProductVideos::find($id);
    }

    public function findOrFail(int $id): ProductVideos
    {
        return ProductVideos::with('product')->findOrFail($id);
    }

    public function getByProductId(int $productId): Collection
    {
        return ProductVideos::query()
            ->where('product_id', $productId)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();
    }

    public function create(ProductVideoData $data): ProductVideos
    {
        $video = new ProductVideos();
        $video->fill($this->prepareCreateAttributes($data));
        $video->save();

        return $video;
    }

    public function update(ProductVideos $video, ProductVideoData $data): bool
    {
        return $video->update($this->prepareUpdateAttributes($data));
    }

    public function delete(ProductVideos $video): bool
    {
        return (bool) $video->delete();
    }

    public function deleteById(int $id): bool
    {
        $video = $this->find($id);

        if ($video === null) {
            return false;
        }

        return $this->delete($video);
    }

    private function prepareCreateAttributes(ProductVideoData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(ProductVideoData $data): array
    {
        return $data->toArray();
    }
}
