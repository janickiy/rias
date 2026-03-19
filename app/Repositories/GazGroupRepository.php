<?php

namespace App\Repositories;

use App\DTO\Admin\GazGroupData;
use App\Models\GazGroup;

class GazGroupRepository
{
    public function create(GazGroupData $data): GazGroup
    {
        return GazGroup::create($data->toArray());
    }

    public function update(GazGroup $group, GazGroupData $data): GazGroup
    {
        $group->update($data->toArray());

        return $group->refresh();
    }

    public function delete(GazGroup $group): void
    {
        $group->remove();
    }
}
