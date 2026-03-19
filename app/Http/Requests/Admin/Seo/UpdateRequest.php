<?php

namespace App\Http\Requests\Admin\Seo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:seo,id'],
            'h1' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'keyword' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'url_canonical' => ['nullable', 'string', 'max:255'],
        ];
    }
}
