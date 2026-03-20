<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentStorageService
{
    /**
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $directory, $filename);

        return $filename;
    }

    /**
     * @param string $directory
     * @param string|null $filename
     * @return void
     */
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
