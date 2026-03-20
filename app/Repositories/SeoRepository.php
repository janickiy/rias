<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\SeoData;
use App\Models\Seo;
use Illuminate\Database\Eloquent\Collection;

class SeoRepository
{
    public function find(int $id): ?Seo
    {
        return Seo::find($id);
    }

    public function findOrFail(int $id): Seo
    {
        return Seo::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Seo::query()
            ->orderBy('id')
            ->get();
    }

    public function update(Seo $seo, SeoData $data): bool
    {
        return $seo->update($this->prepareUpdateAttributes($data));
    }

    private function prepareUpdateAttributes(SeoData $data): array
    {
        return $data->toArray();
    }
}
