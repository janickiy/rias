<?php

namespace App\Repositories;

use App\DTO\Admin\UserData;
use App\Models\User;

class UserRepository
{
    public function create(UserData $data): User
    {
        return User::create($data->toArray());
    }

    public function update(User $user, UserData $data): User
    {
        $user->update($data->toArray());

        return $user->refresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
