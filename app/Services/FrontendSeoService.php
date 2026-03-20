<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Seo;

class FrontendSeoService
{
    /**
     * @param string $type
     * @param string $defaultTitle
     * @return array
     */
    public function getByType(string $type, string $defaultTitle = ''): array
    {
        $seo = Seo::query()->where('type', $type)->first();

        $title = $seo->title ?? $defaultTitle;
        $h1 = $seo->h1 ?? $defaultTitle;

        return [
            'title' => $title,
            'h1' => $h1,
            'meta_description' => $seo->description ?? '',
            'meta_keywords' => $seo->keyword ?? '',
            'meta_title' => $seo->title ?? '',
            'seo_url_canonical' => $seo->url_canonical ?? '',
        ];
    }
}
