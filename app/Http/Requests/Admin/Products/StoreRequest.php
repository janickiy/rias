<?php

namespace App\Http\Requests\Admin\Products;

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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'full_description' => ['required', 'string'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'catalog_id' => ['required', 'integer', 'exists:catalog,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo_h1' => ['nullable', 'string', 'max:255'],
            'seo_url_canonical' => ['nullable', 'string', 'max:255'],
            'image_title' => ['nullable', 'string', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],
        ];
    }
}
