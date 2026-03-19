<?php

namespace App\DTO\Admin;

final class GazData
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $title,
        public readonly ?float $weight,
        public readonly string $chemicalFormula,
        public readonly string $chemicalFormulaHtml,
        public readonly array $gazGroupIds,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            title: (string) $data['title'],
            weight: isset($data['weight']) && $data['weight'] !== '' ? (float) $data['weight'] : null,
            chemicalFormula: (string) $data['chemical_formula'],
            chemicalFormulaHtml: (string) $data['chemical_formula_html'],
            gazGroupIds: array_values(array_filter($data['gaz_group_id'] ?? [], static fn ($value) => is_numeric($value))),
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'weight' => $this->weight,
            'chemical_formula' => $this->chemicalFormula,
            'chemical_formula_html' => $this->chemicalFormulaHtml,
        ];
    }
}
