<?php

namespace App\DTO\Admin;

final class ProductDocumentData
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $path,
        public readonly string $description,
        public readonly int $productId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            path: $data['path'] ?? null,
            description: (string) $data['description'],
            productId: (int) $data['product_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'description' => $this->description,
            'product_id' => $this->productId,
        ];
    }
}
