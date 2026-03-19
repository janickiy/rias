<?php

namespace App\Repositories;

use App\DTO\Admin\ProductPhotoData;
use App\Models\ProductPhotos;

class ProductPhotoRepository
{
    public function create(ProductPhotoData $data): ProductPhotos
    {
        return ProductPhotos::create($data->toArray());
    }

    public function update(ProductPhotos $photo, ProductPhotoData $data): ProductPhotos
    {
        $photo->update($data->toArray());

        return $photo->refresh();
    }

    public function delete(ProductPhotos $photo): void
    {
        $photo->delete();
    }
}
