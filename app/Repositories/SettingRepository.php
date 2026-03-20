<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\SettingData;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;

class SettingRepository
{
    public function find(int $id): ?Settings
    {
        return Settings::find($id);
    }

    public function findOrFail(int $id): Settings
    {
        return Settings::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return Settings::query()
            ->orderBy('id')
            ->get();
    }

    public function create(SettingData $data): Settings
    {
        $setting = new Settings();
        $setting->fill($this->prepareCreateAttributes($data));
        $setting->save();

        return $setting;
    }

    public function update(Settings $setting, SettingData $data): bool
    {
        return $setting->update($this->prepareUpdateAttributes($data));
    }

    public function delete(Settings $setting): bool
    {
        return (bool) $setting->delete();
    }

    public function deleteById(int $id): bool
    {
        $setting = $this->find($id);

        if ($setting === null) {
            return false;
        }

        return $this->delete($setting);
    }

    private function prepareCreateAttributes(SettingData $data): array
    {
        return array_filter(
            $data->toArray(),
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(SettingData $data): array
    {
        return $data->toArray();
    }
}
