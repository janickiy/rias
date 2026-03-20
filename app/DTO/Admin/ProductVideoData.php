<?php

declare(strict_types=1);

namespace App\DTO\Admin;

final class ProductVideoData
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $product_id,
        public readonly ?string $title,
        public readonly string $provider,
        public readonly string $video,
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
            provider: (string) $data['provider'],
            video: (string) $data['video'],
            sort: isset($data['sort']) && $data['sort'] !== '' ? (int) $data['sort'] : null,
            published: isset($data['published']) ? (int) $data['published'] : 0,
        );
    }

    public function toArray(): array
    {
        return [
            'product_id' => $this->product_id,
            'title' => $this->title,
            'provider' => $this->provider,
            'video' => $this->video,
            'sort' => $this->sort,
            'published' => $this->published,
        ];
    }
}
