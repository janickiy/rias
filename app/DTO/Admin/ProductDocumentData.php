<?php

declare(strict_types=1);

namespace App\DTO\Admin;

final class ProductDocumentData
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $product_id,
        public readonly string $title,
        public readonly ?string $path,
        public readonly ?int $sort,
        public readonly ?int $published,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            product_id: (int) $data['product_id'],
            title: (string) $data['title'],
            path: isset($data['path']) && $data['path'] !== '' ? (string) $data['path'] : null,
            sort: isset($data['sort']) && $data['sort'] !== '' ? (int) $data['sort'] : null,
            published: isset($data['published']) ? (int) $data['published'] : 0,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'title' => $this->title,
            'path' => $this->path,
            'sort' => $this->sort,
            'published' => $this->published,
        ];
    }
}
