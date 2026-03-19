<?php

namespace App\Repositories;

use App\DTO\Admin\SettingData;
use App\Models\Settings;

class SettingRepository
{
    public function create(SettingData $data): Settings
    {
        return Settings::create($data->toArray());
    }

    public function update(Settings $setting, SettingData $data): Settings
    {
        $setting->update($data->toArray());

        return $setting->refresh();
    }

    public function delete(Settings $setting): void
    {
        $setting->delete();
    }
}
