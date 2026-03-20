<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ProductDocuments;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:product_documents,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'max:' . $this->maxFileSizeKb()],
            'sort' => ['nullable', 'integer', 'min:0'],
            'published' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->input('id'),
            'sort' => $this->filled('sort') ? (int) $this->input('sort') : null,
            'published' => $this->boolean('published') ? 1 : 0,
        ]);
    }

    private function maxFileSizeKb(): int
    {
        return 10240;
    }
}
