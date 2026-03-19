<?php

namespace App\Repositories;

use App\DTO\Admin\SeoData;
use App\Models\Seo;

class SeoRepository
{
    public function update(Seo $seo, SeoData $data): Seo
    {
        $seo->update($data->toArray());

        return $seo->refresh();
    }
}
