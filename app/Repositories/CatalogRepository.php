<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\CatalogData;
use App\Models\Catalog;

class CatalogRepository
{
    public function find(int $id): ?Catalog
    {
        return Catalog::find($id);
    }

    public function findOrFail(int $id): Catalog
    {
        return Catalog::findOrFail($id);
    }

    public function getOptions(): array
    {
        return Catalog::getOption();
    }

    public function create(CatalogData $data): Catalog
    {
        $catalog = new Catalog();
        $catalog->fill($data->toArray());
        $catalog->save();

        return $catalog;
    }

    public function update(Catalog $catalog, CatalogData $data): bool
    {
        return $catalog->update($data->toArray());
    }

    public function delete(Catalog $catalog): bool
    {
        return (bool) $catalog->delete();
    }
}
