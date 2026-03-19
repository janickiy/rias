<?php

namespace App\Repositories;

use App\DTO\Admin\CatalogData;
use App\Models\Catalog;

class CatalogRepository
{
    public function create(CatalogData $data): Catalog
    {
        return Catalog::create($data->toArray());
    }

    public function update(Catalog $catalog, CatalogData $data): Catalog
    {
        $catalog->update($data->toArray());

        return $catalog->refresh();
    }

    public function delete(Catalog $catalog): void
    {
        $catalog->remove();
    }
}
