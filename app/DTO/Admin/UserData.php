<?php

namespace App\DTO\Admin;

final class UserData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $login,
        public readonly ?string $role,
        public readonly ?string $password,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: (string) $data['name'],
            login: (string) $data['login'],
            role: $data['role'] ?? null,
            password: $data['password'] ?? null,
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'login' => $this->login,
            'role' => $this->role,
            'password' => $this->password,
        ], static fn ($value) => $value !== null && $value !== '');
    }
}
