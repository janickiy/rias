<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\NewsData;
use App\Models\News;
use Illuminate\Database\Eloquent\Collection;

class NewsRepository
{
    public function find(int $id): ?News
    {
        return News::find($id);
    }

    public function findOrFail(int $id): News
    {
        return News::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return News::query()
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();
    }

    public function create(NewsData $data): News
    {
        $news = new News();
        $news->fill($this->prepareAttributes($data));
        $news->save();

        return $news;
    }

    public function update(News $news, NewsData $data): bool
    {
        return $news->update($this->prepareAttributes($data));
    }

    public function delete(News $news): bool
    {
        return (bool) $news->delete();
    }

    public function deleteById(int $id): bool
    {
        $news = $this->find($id);

        if ($news === null) {
            return false;
        }

        return $this->delete($news);
    }

    private function prepareAttributes(NewsData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn ($value) => $value !== null
        );
    }
}
