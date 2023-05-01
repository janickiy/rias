<?php

namespace App\Helpers;

class VideoHelper
{

    /**
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getThumb(string $provider, string $video): string
    {
        if ($provider == 'youtube') {
            return 'https://img.youtube.com/vi/' . $video . '/hqdefault.jpg';
        } else if ($provider == 'rutube') {
            if (@$xml = simplexml_load_file("http://rutube.ru/cgi-bin/xmlapi.cgi?rt_mode=movie&rt_movie_id=" . $video . "&utf=1")) {
                return (string)$xml->thumbnail_url;
            }
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

        if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $link, $out)) {
            $video['provider'] = 'youtube';
            $video['video'] = $out[1];
        } else if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $link, $out)) {
            $video['provider'] = 'youtube';
            $video['video'] = $out[5];
        } else if (preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/video\/embed\/([a-zA-Z0-9_\-]+)/i', $link, $out)) {
            $video['provider'] = 'rutube';
            $video['video'] = $out[1];
        } else if (preg_match('/[http|https]+:\/\/(?:www\.|)rutube\.ru\/tracks\/([a-zA-Z0-9_\-]+)(&.+)?/i', $link, $out)) {
            $video['provider'] = 'rutube';
            $video['video'] = $out[1];
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
        } else if ($provider == 'rutube') {
            $videoplayer = '<iframe width="100%" height="100%" src="//rutube.ru/play/embed/' . $video . '" frameBorder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>  ';
        } else if ($provider == 'mailru') {
            $videoplayer = '<iframe src="//my.mail.ru/video/embed/' . $video . '"  width="100%" height="100%" frameborder="0" scrolling="no" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
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
        } else if ($provider == 'rutube') {
            $url = 'https://rutube.ru/play/embed/' . $video;
        } else if ($provider == 'mailru') {
            $url = 'https://my.mail.ru/video/embed/' . $video;
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
        } else if ($provider == 'rutube') {
            $link = 'https://rutube.ru/video/' . $video . '/';
        }

        return $link;
    }
}
