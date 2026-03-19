<?php

namespace App\Repositories;

use App\DTO\Admin\GazData;
use App\Models\Gaz;
use App\Models\GazToGroup;
use Illuminate\Support\Facades\DB;

class GazRepository
{
    public function create(GazData $data): Gaz
    {
        return DB::transaction(function () use ($data) {
            $gaz = Gaz::create($data->toArray());
            $this->syncGroups($gaz->id, $data->gazGroupIds);

            return $gaz;
        });
    }

    public function update(Gaz $gaz, GazData $data): Gaz
    {
        return DB::transaction(function () use ($gaz, $data) {
            $gaz->update($data->toArray());
            GazToGroup::where('gaz_id', $gaz->id)->delete();
            $this->syncGroups($gaz->id, $data->gazGroupIds);

            return $gaz->refresh();
        });
    }

    public function delete(Gaz $gaz): void
    {
        $gaz->remove();
    }

    private function syncGroups(int $gazId, array $groupIds): void
    {
        foreach ($groupIds as $groupId) {
            GazToGroup::create([
                'gaz_id' => $gazId,
                'gaz_group_id' => (int) $groupId,
            ]);
        }
    }
}
