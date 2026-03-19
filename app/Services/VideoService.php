<?php

namespace App\Services;

use App\Helpers\VideoHelper;

class VideoService
{
    public function parse(string $url): array
    {
        return VideoHelper::detectVideoId($url);
    }
}
