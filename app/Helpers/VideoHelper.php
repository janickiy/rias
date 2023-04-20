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
        } else if (preg_match('/(vk\.com|m\.vk\.com)\/?([^\&\?]*).*/i', $link, $out)) {
            $video['provider'] = 'vk';
            $video['video'] = $out[2];
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
        } else if ($provider == 'vk') {
            preg_match('/video([\d]+)_(\d+)/i', $video, $out);

            $videoplayer = '<iframe src="//vk.com/video_ext.php?oid=' . $out[1] . '&id=' . $out[2] . '&hash=2f5649813d7d7d5f" width="100%" height="100%" frameborder="0" allowfullscreen="1" allow="autoplay; encrypted-media; fullscreen; picture-in-picture"></iframe>';
        }

        return $videoplayer;
    }

    /**
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoUrl(string $provider, string $video): string
    {
        $url = '';

        if ($provider == 'youtube') {
            $url = 'https://www.youtube.com/embed/' . $video;
        } else if ($provider == 'vk') {
            preg_match('/video([\d]+)_(\d+)/i', $video, $out);

            $url = 'https://vk.com/video_ext.php?oid=' . $out[1] . '&id=' . $out[2] . '&hash=2f5649813d7d7d5f';
        }

        return $url;
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
        } else if ($provider == 'vk') {
             $link = 'https://vk.com/' . $video;
        }

        return $link;
    }
}
