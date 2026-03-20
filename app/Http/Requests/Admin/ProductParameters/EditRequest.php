<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ProductParameters;

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
            'id' => ['required', 'integer', 'exists:product_parameters,id'],
            'title' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'string', 'max:65535'],
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
}
