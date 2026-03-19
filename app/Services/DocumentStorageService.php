<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentStorageService
{
    public function store(UploadedFile $file, string $directory): string
    {
        $filename = uniqid('', true) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/' . $directory, $filename);

        return $filename;
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
