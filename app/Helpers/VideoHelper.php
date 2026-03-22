<?php

namespace App\Helpers;

class VideoHelper
{
    private const PROVIDER_YOUTUBE = 'youtube';
    private const PROVIDER_RUTUBE = 'rutube';
    private const PROVIDER_MAILRU = 'mailru';

    /**
     * Получить ссылку на превью.
     */
    public static function getThumb(string $provider, string $video): string
    {
        return match ($provider) {
            self::PROVIDER_YOUTUBE => 'https://img.youtube.com/vi/' . $video . '/hqdefault.jpg',
            self::PROVIDER_RUTUBE => self::getRutubeThumb($video) ?? url('img/video.jpg'),
            self::PROVIDER_MAILRU => self::getMailRuPoster($video) ?? url('img/video.jpg'),
            default => url('img/video.jpg'),
        };
    }

    /**
     * Определить провайдера и id видео по ссылке.
     *
     * @return array{provider?: string, video?: string}
     */
    public static function detectVideoId(string $link): array
    {
        $link = trim($link);

        if ($link === '') {
            return [];
        }

        // youtube watch?v=
        if (preg_match('~https?://(?:www\.)?youtube\.com/watch\?(?:.*&)?v=([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_YOUTUBE,
                'video' => $out[1],
            ];
        }

        // youtube embed
        if (preg_match('~https?://(?:www\.)?youtube\.com/embed/([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_YOUTUBE,
                'video' => $out[1],
            ];
        }

        // youtube shorts
        if (preg_match('~https?://(?:www\.)?youtube\.com/shorts/([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_YOUTUBE,
                'video' => $out[1],
            ];
        }

        // youtu.be
        if (preg_match('~https?://youtu\.be/([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_YOUTUBE,
                'video' => $out[1],
            ];
        }

        // rutube embed
        if (preg_match('~https?://(?:www\.)?rutube\.ru/video/embed/([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_RUTUBE,
                'video' => $out[1],
            ];
        }

        // rutube video
        if (preg_match('~https?://(?:www\.)?rutube\.ru/video/([\w\-]+)~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_RUTUBE,
                'video' => $out[1],
            ];
        }

        // старый формат rutube
        if (preg_match('~https?://(?:www\.)?rutube\.ru/tracks/([\w\-]+)(?:.+)?~i', $link, $out)) {
            return [
                'provider' => self::PROVIDER_RUTUBE,
                'video' => $out[1],
            ];
        }

        // my.mail.ru
        if (preg_match('~https?://my\.mail\.ru/.+\.html~i', $link)) {
            return [
                'provider' => self::PROVIDER_MAILRU,
                'video' => self::getMailRuVideoId($link),
            ];
        }

        return [];
    }

    /**
     * Получить HTML плеера.
     *
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoPlayer(string $provider, string $video): string
    {
        $url = self::getVideoUrl($provider, $video);

        if ($url === '') {
            return '';
        }

        return match ($provider) {
            self::PROVIDER_YOUTUBE => sprintf(
                '<iframe width="100%%" height="100%%" src="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                e($url)
            ),
            self::PROVIDER_RUTUBE => sprintf(
                '<iframe width="100%%" height="100%%" src="%s" frameborder="0" allow="clipboard-write; autoplay" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                e($url)
            ),
            self::PROVIDER_MAILRU => sprintf(
                '<iframe width="100%%" height="100%%" src="%s" frameborder="0" scrolling="no" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
                e($url)
            ),
            default => '',
        };
    }

    /**
     * Получить embed URL.
     *
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoUrl(string $provider, string $video): string
    {
        return match ($provider) {
            self::PROVIDER_YOUTUBE => 'https://www.youtube.com/embed/' . $video,
            self::PROVIDER_RUTUBE => 'https://rutube.ru/play/embed/' . $video,
            self::PROVIDER_MAILRU => self::getMailRuEmbedUrl($video) ?? '',
            default => '',
        };
    }

    /**
     * Получить публичную ссылку на видео.
     *
     * @param string $provider
     * @param string $video
     * @return string
     */
    public static function getVideoLink(string $provider, string $video): string
    {
        return match ($provider) {
            self::PROVIDER_YOUTUBE => 'https://www.youtube.com/watch?v=' . $video,
            self::PROVIDER_RUTUBE => 'https://rutube.ru/video/' . $video . '/',
            self::PROVIDER_MAILRU => self::getMailRuPublicUrl($video) ?? '',
            default => '',
        };
    }

    /**
     * Преобразование Mail.ru URL в ID формата для API.
     *
     * @param string $url
     * @return string
     */
    private static function getMailRuVideoId(string $url): string
    {
        $result = str_replace('https://my.mail.ru/', '', $url);
        $result = str_replace('http://my.mail.ru/', '', $result);
        $result = str_replace('/video/', '/', $result);
        $result = str_replace('.html', '', $result);

        return trim($result, '/');
    }

    /**
     * Получить превью Rutube.
     *
     * @param string $video
     * @return string|null
     */
    private static function getRutubeThumb(string $video): ?string
    {
        $xmlContent = @file_get_contents(
            'https://rutube.ru/cgi-bin/xmlapi.cgi?rt_mode=movie&rt_movie_id=' . urlencode($video) . '&utf=1'
        );

        if ($xmlContent === false) {
            return null;
        }

        $xml = simplexml_load_string($xmlContent);

        if ($xml === false || empty($xml->thumbnail_url)) {
            return null;
        }

        return str_replace('http://', 'https://', (string) $xml->thumbnail_url);
    }

    /**
     * Получить poster Mail.ru.
     *
     * @param string $video
     * @return string|null
     */
    private static function getMailRuPoster(string $video): ?string
    {
        $data = self::getMailRuVideoData($video);

        return $data['meta']['poster'] ?? null;
    }

    /**
     * Получить embed URL Mail.ru.
     *
     * @param string $video
     * @return string|null
     */
    private static function getMailRuEmbedUrl(string $video): ?string
    {
        $data = self::getMailRuVideoData($video);
        $id = $data['meta']['id'] ?? null;

        if (!$id) {
            return null;
        }

        return 'https://my.mail.ru/video/embed/' . $id;
    }

    /**
     * Получить публичный URL Mail.ru.
     *
     * @param string $video
     * @return string|null
     */
    private static function getMailRuPublicUrl(string $video): ?string
    {
        $data = self::getMailRuVideoData($video);

        return $data['meta']['url'] ?? null;
    }

    /**
     * Получить данные видео Mail.ru.
     *
     * @param string $video
     * @return array
     */
    private static function getMailRuVideoData(string $video): array
    {
        $url = 'https://videoapi.my.mail.ru/videos/' . ltrim($video, '/') . '.json';

        $context = stream_context_create([
            'http' => [
                'timeout' => 5,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            return [];
        }

        $decoded = json_decode($result, true);

        return is_array($decoded) ? $decoded : [];
    }
}
