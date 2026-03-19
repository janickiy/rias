<?php

namespace App\DTO\Admin;

final class ProductVideoData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $provider,
        public readonly string $video,
        public readonly int $productId,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            provider: (string) $data['provider'],
            video: (string) $data['video'],
            productId: (int) $data['product_id'],
        );
    }

    public function toArray(): array
    {
        return [
            'provider' => $this->provider,
            'video' => $this->video,
            'product_id' => $this->productId,
        ];
    }
}
