<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\GazGroupData;
use App\Models\GazGroup;
use Illuminate\Support\Collection;

class GazGroupRepository
{
    public function find(int $id): ?GazGroup
    {
        return GazGroup::find($id);
    }

    public function findOrFail(int $id): GazGroup
    {
        return GazGroup::findOrFail($id);
    }

    public function create(GazGroupData $data): GazGroup
    {
        return GazGroup::create($data->toArray());
    }

    public function update(GazGroup $gazGroup, GazGroupData $data): bool
    {
        return $gazGroup->update($data->toArray());
    }

    public function delete(GazGroup $gazGroup): bool
    {
        return (bool) $gazGroup->delete();
    }

    public function getOptions(): Collection
    {
        return GazGroup::getOption();
    }
}
