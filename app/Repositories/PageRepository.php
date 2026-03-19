<?php

namespace App\Repositories;

use App\DTO\Admin\PageData;
use App\Models\Pages;

class PageRepository
{
    public function create(PageData $data): Pages
    {
        if ($data->main) {
            Pages::query()->update(['main' => 0]);
        }

        return Pages::create($data->toArray());
    }

    public function update(Pages $page, PageData $data): Pages
    {
        if ($data->main) {
            Pages::where('id', '!=', $page->id)->update(['main' => 0]);
        }

        $page->update($data->toArray());

        return $page->refresh();
    }

    public function delete(Pages $page): void
    {
        $page->delete();
    }
}
