<?php

declare(strict_types=1);

namespace App\DTO\Admin;

final class ProductPhotoData
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $product_id,
        public readonly ?string $title,
        public readonly ?string $origin,
        public readonly ?string $thumbnail,
        public readonly ?int $sort,
        public readonly ?int $published,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            product_id: (int) $data['product_id'],
            title: isset($data['title']) && $data['title'] !== '' ? (string) $data['title'] : null,
            origin: isset($data['origin']) && $data['origin'] !== '' ? (string) $data['origin'] : null,
            thumbnail: isset($data['thumbnail']) && $data['thumbnail'] !== '' ? (string) $data['thumbnail'] : null,
            sort: isset($data['sort']) && $data['sort'] !== '' ? (int) $data['sort'] : null,
            published: isset($data['published']) ? (int) $data['published'] : 0,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'title' => $this->title,
            'origin' => $this->origin,
            'thumbnail' => $this->thumbnail,
            'sort' => $this->sort,
            'published' => $this->published,
        ];
    }
}
