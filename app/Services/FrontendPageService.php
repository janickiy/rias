<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Catalog;
use App\Models\News;
use App\Models\Pages;
use App\Models\Products;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FrontendPageService
{
    /**
     * @return Pages
     */
    public function getMainPage():Pages
    {
        return Pages::query()->where('main', 1)->firstOrFail();
    }

    /**
     * @param string $slug
     * @return Pages
     */
    public function getPageBySlug(string $slug): Pages
    {
        return Pages::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getNewsPaginated(int $perPage = 5): LengthAwarePaginator
    {
        return News::query()->paginate($perPage);
    }

    public function getNewsBySlug(string $slug): News
    {
        return News::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getRandomProducts(int $limit = 3): Collection
    {
        return Products::query()->inRandomOrder()->limit($limit)->get();
    }

    /**
     * @param string $slug
     * @return Products
     */
    public function getProductBySlug(string $slug): Products
    {
        return Products::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param string $slug
     * @return Catalog
     */
    public function getCatalogBySlug(string $slug): Catalog
    {
        return Catalog::query()->where('slug', $slug)->firstOrFail();
    }

    /**
     * @param int $catalogId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCatalogProductsPaginated(int $catalogId, int $perPage = 6): LengthAwarePaginator
    {
        return Products::query()
            ->where('catalog_id', $catalogId)
            ->paginate($perPage);
    }

    public function getCatalogList(): Collection
    {
        return Catalog::query()->orderBy('name')->get();
    }
}
