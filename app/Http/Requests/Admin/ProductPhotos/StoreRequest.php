<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ProductPhotos;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:10240'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'published' => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'product_id' => $this->input('product_id'),
            'sort' => $this->filled('sort') ? (int) $this->input('sort') : null,
            'published' => $this->boolean('published') ? 1 : 0,
        ]);
    }
}
