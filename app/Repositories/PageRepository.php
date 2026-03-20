<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\PageData;
use App\Models\Pages;
use Illuminate\Database\Eloquent\Collection;

class PageRepository
{
    public function find(int $id): ?Pages
    {
        return Pages::find($id);
    }

    public function findOrFail(int $id): Pages
    {
        return Pages::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Pages::query()
            ->orderBy('id')
            ->get();
    }

    public function getPublished(): Collection
    {
        return Pages::query()
            ->published()
            ->orderBy('id')
            ->get();
    }

    public function getParentOptions(): array
    {
        return Pages::query()
            ->published()
            ->orderBy('id')
            ->pluck('title', 'id')
            ->all();
    }

    public function create(PageData $data): Pages
    {
        $page = new Pages();
        $page->fill($this->prepareCreateAttributes($data));
        $page->save();

        return $page;
    }

    public function update(Pages $page, PageData $data): bool
    {
        return $page->update($this->prepareUpdateAttributes($data));
    }

    public function delete(Pages $page): bool
    {
        return (bool) $page->delete();
    }

    public function deleteById(int $id): bool
    {
        $page = $this->find($id);

        if ($page === null) {
            return false;
        }

        return $this->delete($page);
    }

    private function prepareCreateAttributes(PageData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn ($value) => $value !== null
        );
    }

    private function prepareUpdateAttributes(PageData $data): array
    {
        return $data->toArray();
    }
}
