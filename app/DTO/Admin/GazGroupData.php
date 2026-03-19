<?php

namespace App\DTO\Admin;

final class GazGroupData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $nameRu,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: (string) $data['name'],
            nameRu: (string) $data['name_ru'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'name_ru' => $this->nameRu,
        ];
    }
}
