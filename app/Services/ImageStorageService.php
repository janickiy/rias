<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageStorageService
{
    /**
     * @param UploadedFile $file
     * @param string $directory
     * @param int|null $width
     * @param int|null $height
     * @param string $prefix
     * @return string
     */
    public function storeResizedImage(UploadedFile $file, string $directory, ?int $width = null, ?int $height = null, string $prefix = ''): string
    {
        $filename = $prefix . uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $directory, $filename);

        $image = Image::make(Storage::path('public/' . $directory . '/' . $filename));
        $image->resize($width, $height, static function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save(Storage::path('public/' . $directory . '/' . $filename));

        return $filename;
    }

    /**
     * @param UploadedFile $file
     * @param string $directory
     * @param int|null $thumbWidth
     * @param int|null $thumbHeight
     * @return string[]
     */
    public function storeOriginalAndThumbnail(UploadedFile $file, string $directory, ?int $thumbWidth = null, ?int $thumbHeight = null): array
    {
        $basename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $origin = 'origin_' . $basename;
        $thumbnail = 'thumbnail_' . $basename;

        $file->storeAs('public/' . $directory, $origin);

        $image = Image::make(Storage::path('public/' . $directory . '/' . $origin));
        $image->resize($thumbWidth, $thumbHeight, static function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $image->save(Storage::path('public/' . $directory . '/' . $thumbnail));

        return [
            'origin' => $origin,
            'thumbnail' => $thumbnail,
        ];
    }

    public function deleteIfExists(string $directory, ?string $filename): void
    {
        if (empty($filename)) {
            return;
        }

        if (Storage::disk('public')->exists($directory . '/' . $filename)) {
            Storage::disk('public')->delete($directory . '/' . $filename);
        }
    }
}
