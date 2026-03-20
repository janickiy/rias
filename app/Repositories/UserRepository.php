<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTO\Admin\UserData;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findOrFail(int $id): User
    {
        return User::findOrFail($id);
    }

    public function getAll(): Collection
    {
        return User::query()
            ->orderBy('id')
            ->get();
    }

    public function create(UserData $data): User
    {
        $user = new User();
        $user->fill($this->prepareCreateAttributes($data));
        $user->save();

        return $user;
    }

    public function update(User $user, UserData $data): bool
    {
        return $user->update($this->prepareUpdateAttributes($data, $user));
    }

    public function delete(User $user): bool
    {
        return (bool) $user->delete();
    }

    public function deleteById(int $id): bool
    {
        $user = $this->find($id);

        if ($user === null) {
            return false;
        }

        return $this->delete($user);
    }

    private function prepareCreateAttributes(UserData $data): array
    {
        $attributes = $data->toArray();

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return array_filter(
            $attributes,
            static fn (mixed $value): bool => $value !== null
        );
    }

    private function prepareUpdateAttributes(UserData $data, User $user): array
    {
        $attributes = $data->toArray();

        if (!empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        } else {
            unset($attributes['password']);
        }

        return $attributes;
    }
}
