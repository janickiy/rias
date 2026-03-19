<?php

namespace App\DTO\Admin;

final class PageData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly string $text,
        public readonly ?string $metaTitle,
        public readonly ?string $metaDescription,
        public readonly ?string $metaKeywords,
        public readonly string $slug,
        public readonly ?int $parentId,
        public readonly bool $published,
        public readonly bool $main,
        public readonly ?string $seoH1,
        public readonly ?string $seoUrlCanonical,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            title: (string) $data['title'],
            text: (string) $data['text'],
            metaTitle: $data['meta_title'] ?? null,
            metaDescription: $data['meta_description'] ?? null,
            metaKeywords: $data['meta_keywords'] ?? null,
            slug: (string) $data['slug'],
            parentId: isset($data['parent_id']) ? (int) $data['parent_id'] : 0,
            published: (bool) ($data['published'] ?? false),
            main: (bool) ($data['main'] ?? false),
            seoH1: $data['seo_h1'] ?? null,
            seoUrlCanonical: $data['seo_url_canonical'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'meta_keywords' => $this->metaKeywords,
            'slug' => $this->slug,
            'parent_id' => $this->parentId ?? 0,
            'published' => $this->published,
            'main' => $this->main,
            'seo_h1' => $this->seoH1,
            'seo_url_canonical' => $this->seoUrlCanonical,
        ];
    }
}
