<?php

namespace App\Repositories;

use App\DTO\Admin\ProductVideoData;
use App\Models\ProductVideos;

class ProductVideoRepository
{
    public function create(ProductVideoData $data): ProductVideos
    {
        return ProductVideos::create($data->toArray());
    }

    public function update(ProductVideos $video, ProductVideoData $data): ProductVideos
    {
        $video->update($data->toArray());

        return $video->refresh();
    }

    public function delete(ProductVideos $video): void
    {
        $video->delete();
    }
}
