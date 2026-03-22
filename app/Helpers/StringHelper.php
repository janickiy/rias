<?php

declare(strict_types=1);

namespace App\Helpers;

class StringHelper
{
    /**
     * Транслитерация для slug.
     */
    private const SLUG_MAP = [
        'А' => 'A',   'Б' => 'B',   'В' => 'V',   'Г' => 'G',   'Д' => 'D',
        'Е' => 'E',   'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',   'И' => 'I',
        'Й' => 'Y',   'К' => 'K',   'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',   'С' => 'S',   'Т' => 'T',
        'У' => 'U',   'Ф' => 'F',   'Х' => 'H',   'Ц' => 'Ts',  'Ч' => 'Ch',
        'Ш' => 'Sh',  'Щ' => 'Sch', 'Ъ' => '',    'Ы' => 'Y',   'Ь' => '',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',

        'а' => 'a',   'б' => 'b',   'в' => 'v',   'г' => 'g',   'д' => 'd',
        'е' => 'e',   'ё' => 'e',   'ж' => 'zh',  'з' => 'z',   'и' => 'i',
        'й' => 'y',   'к' => 'k',   'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',   'с' => 's',   'т' => 't',
        'у' => 'u',   'ф' => 'f',   'х' => 'h',   'ц' => 'ts',  'ч' => 'ch',
        'ш' => 'sh',  'щ' => 'sch', 'ъ' => '',    'ы' => 'y',   'ь' => '',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
    ];

    private const MIME_TYPES = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'webp' => 'image/webp',

        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        '7z' => 'application/x-7z-compressed',
        'tar' => 'application/x-tar',
        'gz' => 'application/gzip',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg',
        'mp4' => 'video/mp4',
        'avi' => 'video/x-msvideo',
        'mov' => 'video/quicktime',
        'qt' => 'video/quicktime',
        'webm' => 'video/webm',

        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        'doc' => 'application/msword',
        'dot' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',

        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        'csv' => 'text/csv',
    ];

    /**
     * Рекурсивное преобразование объекта в массив.
     *
     * @param mixed $data
     * @return mixed
     */
    public static function objectToArray(mixed $data): mixed
    {
        if (!is_array($data) && !is_object($data)) {
            return $data;
        }

        $result = [];

        foreach ((array) $data as $key => $value) {
            $result[$key] = self::objectToArray($value);
        }

        return $result;
    }

    /**
     * @param mixed $element
     * @param bool $first
     * @return string
     */
    public static function tree(mixed $element, bool $first = true): string
    {
        if (is_object($element)) {
            $element = (array) $element;
        }

        if (!is_array($element) || empty($element)) {
            return '';
        }

        $out = $first
            ? '<ul id="tree-checkbox" class="tree-checkbox treeview">'
            : '<ul>';

        foreach ($element as $key => $value) {
            if ($value === null || $value === '' || $value === [] || $value === false) {
                continue;
            }

            if (is_object($value)) {
                $value = (array) $value;
            }

            $safeKey = htmlspecialchars((string) $key, ENT_QUOTES, 'UTF-8');

            $out .= '<li><strong>' . $safeKey . ':</strong> ';

            if (is_array($value)) {
                $out .= self::tree($value, false);
            } else {
                $out .= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            }

            $out .= '</li>';
        }

        $out .= '</ul>';

        return $out;
    }

    /**
     * Генерация slug.
     *
     * @param string $text
     * @param bool $toLower
     * @return string
     */
    public static function slug(string $text, bool $toLower = true): string
    {
        $text = trim($text);

        if ($text === '') {
            return '';
        }

        $text = strtr($text, self::SLUG_MAP);

        // Убираем кавычки и спецсимволы, заменяем всё лишнее на дефис
        $text = preg_replace('/[\'"`^«»№’ˮ]/u', '', $text) ?? $text;
        $text = preg_replace('/[^A-Za-z0-9]+/u', '-', $text) ?? $text;
        $text = trim($text, '-');

        if ($toLower) {
            $text = mb_strtolower($text);
        }

        return $text;
    }

    /**
     * Обрезка текста по словам.
     *
     * @param string $str
     * @param int $chars
     * @return string
     */
    public static function shortText(string $str, int $chars = 500): string
    {
        $str = trim(strip_tags($str));

        if ($str === '') {
            return '';
        }

        if (mb_strlen($str) <= $chars) {
            return $str;
        }

        $cut = mb_substr($str, 0, $chars);
        $rest = mb_substr($str, $chars);

        $spacePos = mb_strpos($rest, ' ');

        if ($spacePos !== false) {
            $cut .= mb_substr($rest, 0, $spacePos);
        }

        return rtrim($cut) . '...';
    }

    /**
     * Форматирование размера в MB.
     *
     * @param int $size
     * @param int $maxDecimals
     * @param string $mbSuffix
     * @return string
     */
    public static function formatSizeInMb(int $size, int $maxDecimals = 3, string $mbSuffix = 'MB'): string
    {
        $mbSize = round($size / 1024 / 1024, $maxDecimals);
        $formatted = rtrim(rtrim((string) $mbSize, '0'), '.');

        return $formatted . $mbSuffix;
    }


    /**
     * Максимальный допустимый размер загружаемого файла в байта
     *
     * @return int
     */
    public static function detectMaxUploadFileSize(): int
    {
        $normalize = static function (string|false $size): int|false {
            if ($size === false || $size === '') {
                return false;
            }

            $size = trim($size);

            if (!preg_match('/^(-?[\d.]+)\s*([KMG]?)$/i', $size, $match)) {
                return false;
            }

            $value = (float) $match[1];
            $unit = strtoupper($match[2]);

            $power = match ($unit) {
                'K' => 1,
                'M' => 2,
                'G' => 3,
                default => 0,
            };

            return (int) round($value * (1024 ** $power));
        };

        $limits = [];

        $uploadMax = $normalize(ini_get('upload_max_filesize'));
        if ($uploadMax !== false && $uploadMax > 0) {
            $limits[] = $uploadMax;
        }

        $postMax = $normalize(ini_get('post_max_size'));
        if ($postMax !== false && $postMax > 0) {
            $limits[] = $postMax;
        }

        $memoryLimit = $normalize(ini_get('memory_limit'));
        if ($memoryLimit !== false && $memoryLimit > 0) {
            $limits[] = $memoryLimit;
        }

        if (empty($limits)) {
            return 2097152; // 2 MB
        }

        return min($limits);
    }

    /**
     * Максимальный размер загрузки в формате строки.
     *
     * @return string
     */
    public static function maxUploadFileSize(): string
    {
        return self::formatSizeInMb(self::detectMaxUploadFileSize());
    }

    /**
     * MIME по файлу изображения.
     *
     * @param string $path
     * @return string|null
     */
    public static function getMime(string $path): ?string
    {
        if (!is_file($path) || !is_readable($path)) {
            return null;
        }

        $imageInfo = @getimagesize($path);

        return $imageInfo['mime'] ?? null;
    }

    /**
     * Нормализация телефона.
     *
     * @param string $string
     * @return string
     */
    public static function phone(string $string): string
    {
        return preg_replace('/[^\d+]/', '', trim($string)) ?? '';
    }

    /**
     * MIME type по расширению файла.
     *
     * @param string $filename
     * @return string
     */
    public static function getMimeType(string $filename): string
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($extension === '') {
            return 'application/octet-stream';
        }

        return self::MIME_TYPES[$extension] ?? 'application/octet-stream';
    }

    /**
     * Алиас для обратной совместимости.
     *
     * @param string $filename
     * @return string
     */
    public static function get_mime_type(string $filename): string
    {
        return self::getMimeType($filename);
    }
}
