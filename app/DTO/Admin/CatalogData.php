<?php

namespace App\DTO\Admin;

final class CatalogData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly ?string $scope,
        public readonly ?string $metaTitle,
        public readonly ?string $metaDescription,
        public readonly ?string $metaKeywords,
        public readonly ?string $image,
        public readonly string $slug,
        public readonly ?string $seoH1,
        public readonly ?string $seoUrlCanonical,
        public readonly ?string $imageTitle,
        public readonly ?string $imageAlt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            name: (string) $data['name'],
            description: $data['description'] ?? null,
            scope: $data['scope'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords: $data['meta_keywords'] ?? null,
            image: $data['image'] ?? null,
            slug: (string) $data['slug'],
            seoH1: $data['seo_h1'] ?? null,
            seoUrlCanonical: $data['seo_url_canonical'] ?? null,
            imageTitle: $data['image_title'] ?? null,
            imageAlt: $data['image_alt'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'scope' => $this->scope,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'image' => $this->image,
            'slug' => $this->slug,
            'seo_h1' => $this->seoH1,
            'seo_url_canonical' => $this->seoUrlCanonical,
            'image_title' => $this->imageTitle,
            'image_alt' => $this->imageAlt,
        ];
    }
}
