<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SettingsHelper;
use App\Mail\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ApplicationService
{
    /**
     * @param UploadedFile|null $attachment
     * @return void
     */
    public function send(?UploadedFile $attachment): void
    {
        if ($attachment === null) {
            throw new \RuntimeException('Файл не был загружен');
        }

        $filename = $this->storeAttachment($attachment);
        $emails = $this->getNotificationEmails();

        if (empty($emails)) {
            throw new \RuntimeException('Не настроены email для уведомлений');
        }

        Mail::to($emails)->send(new Notification($filename));
    }

    /**
     * @param UploadedFile $attachment
     * @return string
     */
    private function storeAttachment(UploadedFile $attachment): string
    {
        $path = public_path('uploads');

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        $safeName = preg_replace(
            '/[^A-Za-z0-9_\-.]/',
            '',
            $attachment->getClientOriginalName()
        );

        $name = time() . '_' . $safeName;

        $attachment->move($path, $name);

        return $path . DIRECTORY_SEPARATOR . $name;
    }

    private function getNotificationEmails(): array
    {
        $setting = SettingsHelper::getSetting('EMAIL_NOTIFY');

        return array_values(
            array_filter(
                array_map('trim', explode(',', $setting))
            )
        );
    }
}
