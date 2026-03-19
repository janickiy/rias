<?php

namespace App\DTO\Admin;

final class SettingData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $keyCd,
        public readonly string $type,
        public readonly ?string $displayValue,
        public readonly string $value,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            keyCd: (string) $data['key_cd'],
            type: (string) $data['type'],
            displayValue: $data['display_value'] ?? null,
            value: (string) $data['value'],
        );
    }

    public function toArray(): array
    {
        return [
            'key_cd' => $this->keyCd,
            'type' => $this->type,
            'display_value' => $this->displayValue,
            'value' => $this->value,
        ];
    }
}
