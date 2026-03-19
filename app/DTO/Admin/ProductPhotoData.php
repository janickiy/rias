<?php

namespace App\DTO\Admin;

final class ProductPhotoData
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $title,
        public readonly ?string $alt,
        public readonly ?string $thumbnail,
        public readonly ?string $origin,
        public readonly int $productId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            title: $data['title'] ?? null,
            alt: $data['alt'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            origin: $data['origin'] ?? null,
            productId: (int) $data['product_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'alt' => $this->alt,
            'thumbnail' => $this->thumbnail,
            'origin' => $this->origin,
            'product_id' => $this->productId,
        ];
    }
}
