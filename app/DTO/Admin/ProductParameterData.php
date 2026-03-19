<?php

namespace App\DTO\Admin;

final class ProductParameterData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly string $value,
        public readonly int $productId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: (string) $data['name'],
            value: (string) $data['value'],
            productId: (int) $data['product_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'product_id' => $this->productId,
        ];
    }
}
