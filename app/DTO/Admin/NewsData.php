<?php

namespace App\DTO\Admin;

final class NewsData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly string $preview,
        public readonly string $text,
        public readonly ?string $image,
        public readonly ?string $imageTitle,
        public readonly ?string $imageAlt,
        public readonly ?string $metaTitle,
        public readonly ?string $metaDescription,
        public readonly ?string $metaKeywords,
        public readonly string $slug,
        public readonly ?string $seoH1,
        public readonly ?string $seoUrlCanonical,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            title: (string) $data['title'],
            preview: (string) $data['preview'],
            text: (string) $data['text'],
            image: $data['image'] ?? null,
            imageTitle: $data['image_title'] ?? null,
            imageAlt: $data['image_alt'] ?? null,
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords: $data['meta_keywords'] ?? null,
            slug: (string) $data['slug'],
            seoH1: $data['seo_h1'] ?? null,
            seoUrlCanonical: $data['seo_url_canonical'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'preview' => $this->preview,
            'text' => $this->text,
            'image' => $this->image,
            'image_title' => $this->imageTitle,
            'image_alt' => $this->imageAlt,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'slug' => $this->slug,
            'seo_h1' => $this->seoH1,
            'seo_url_canonical' => $this->seoUrlCanonical,
        ];
    }
}
