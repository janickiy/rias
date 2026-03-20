<?php

namespace App\Repositories;

use App\DTO\Admin\GazData;
use App\Models\Gaz;
use App\Models\GazToGroup;
use Illuminate\Support\Facades\DB;

class GazRepository
{

    public function find(int $id): ?Gaz
    {
        return Gaz::find($id);
    }

    public function findOrFail(int $id): Gaz
    {
        return Gaz::with('groups')->findOrFail($id);
    }

    public function create(GazData $data): Gaz
    {
        $gaz = Gaz::create($data->toArray());

        if (!empty($data->gaz_group_id)) {
            $gaz->groups()->sync($data->gaz_group_id);
        }

        return $gaz;
    }

    public function update(Gaz $gaz, GazData $data): bool
    {
        $updated = $gaz->update($data->toArray());

        if (isset($data->gaz_group_id)) {
            $gaz->groups()->sync($data->gaz_group_id);
        }

        return $updated;
    }

    public function delete(Gaz $gaz): bool
    {
        $gaz->groups()->detach();

        return (bool)$gaz->delete();
    }

    public function getGroupIds(Gaz $gaz): array
    {
        return $gaz->groups()->pluck('id')->all();
    }

    private function syncGroups(int $gazId, array $groupIds): void
    {
        foreach ($groupIds as $groupId) {
            GazToGroup::create([
                'gaz_id' => $gazId,
                'gaz_group_id' => (int)$groupId,
            ]);
        }
    }
}
