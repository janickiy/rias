<?php

namespace App\DTO\Admin;

final class ProductData
{
    public function __construct(
        public readonly ?int    $id,
        public readonly string  $title,
        public readonly string  $description,
        public readonly string  $fullDescription,
        public readonly int     $catalogId,
        public readonly ?string $metaTitle,
        public readonly ?string $metaDescription,
        public readonly ?string $metaKeywords,
        public readonly string  $slug,
        public readonly ?string $seoH1,
        public readonly ?string $seoUrlCanonical,
        public readonly ?string $thumbnail,
        public readonly ?string $origin,
        public readonly ?string $imageTitle,
        public readonly ?string $imageAlt,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int)$data['id'] : null,
            title: (string)$data['title'],
            description: (string)$data['description'],
            fullDescription: (string)$data['full_description'],
            catalogId: (int)$data['catalog_id'],
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords: $data['meta_keywords'] ?? null,
            slug: (string)$data['slug'],
            seoH1: $data['seo_h1'] ?? null,
            seoUrlCanonical: $data['seo_url_canonical'] ?? null,
            thumbnail: $data['thumbnail'] ?? null,
            origin: $data['origin'] ?? null,
            imageTitle: $data['image_title'] ?? null,
            imageAlt: $data['image_alt'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'full_description' => $this->fullDescription,
            'catalog_id' => $this->catalogId,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'slug' => $this->slug,
            'seo_h1' => $this->seoH1,
            'seo_url_canonical' => $this->seoUrlCanonical,
            'thumbnail' => $this->thumbnail,
            'origin' => $this->origin,
            'image_title' => $this->imageTitle,
            'image_alt' => $this->imageAlt,
        ];
    }
}
