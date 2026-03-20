<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\File;

class RobotsService
{
    private string $path;

    public function __construct()
    {
        $this->path = public_path('robots.txt');
    }

    public function getContent(): string
    {
        if (!File::exists($this->path)) {
            return '';
        }

        return File::get($this->path);
    }

    /**
     * @param string $content
     * @return void
     */
    public function update(string $content): void
    {
        File::put($this->path, $content);
    }
}
