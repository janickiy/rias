<?php

namespace App\DTO\Admin;

final class SeoData
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $h1,
        public readonly ?string $title,
        public readonly ?string $keyword,
        public readonly ?string $description,
        public readonly ?string $urlCanonical,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            h1: $data['h1'] ?? null,
            title: $data['title'] ?? null,
            keyword: $data['keyword'] ?? null,
            description: $data['description'] ?? null,
            urlCanonical: $data['url_canonical'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'h1' => $this->h1,
            'title' => $this->title,
            'keyword' => $this->keyword,
            'description' => $this->description,
            'url_canonical' => $this->urlCanonical,
        ];
    }
}
