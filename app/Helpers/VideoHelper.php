<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * @param $provider
     * @param $video
     * @return string
     */
    public static function getThumb(string $provider, string $video): string
    {
        if ($provider == 'youtube') {
            return 'https://img.youtube.com/vi/' . $video . '/hqdefault.jpg';
        } else {
            return url('img/video.jpg');
        }
    }

    /**
     * @param string $link
     * @return array
     */
    public static function detectVideoId(string $link): array
    {
        $video = [];

        if (preg_match('/youtu\.be\/([^\?]*)/', $link, $out)) {
            $video['provider'] = 'youtube';
            $video['video'] = $out[1];
        } else if (preg_match('/^.*((v\/)|(embed\/)|(watch\?))\??v?=?([^\&\?]*).*/', $link, $out)) {
            $video['provider'] = 'youtube';
            $video['video'] = $out[5];
        }

        return $video;
    }

    /**
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoPlayer(string $provider, string $video): string
    {
        $videoplayer = '';

        if ($provider == 'youtube') {
            $videoplayer = '<iframe width="100%" height="100%" src="//www.youtube.com/embed/' . $video . '?frameborder="0" allowfullscreen></iframe>';
        }

        return $videoplayer;
    }

    /**
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoLink(string $provider, string $video): string
    {
        $link = '';

        if ($provider == 'youtube') {
            $link = 'https://www.youtube.com/watch?v=' . $video;
        }

        return $link;
    }
}
