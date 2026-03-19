<?php

namespace App\Repositories;

use App\DTO\Admin\NewsData;
use App\Models\News;

class NewsRepository
{
    public function create(NewsData $data): News
    {
        return News::create($data->toArray());
    }

    public function update(News $news, NewsData $data): News
    {
        $news->update($data->toArray());

        return $news->refresh();
    }

    public function delete(News $news): void
    {
        $news->delete();
    }
}
