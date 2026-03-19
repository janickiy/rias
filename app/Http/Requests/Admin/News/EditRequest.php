<?php

namespace App\Http\Requests\Admin\News;

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
            'id' => ['required', 'integer', 'exists:news,id'],
            'title' => ['required', 'string', 'max:255'],
            'preview' => ['required', 'string'],
            'text' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
            'image_title' => ['nullable', 'string', 'max:255'],
            'image_alt' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:news,slug,' . $this->id],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'seo_h1' => ['nullable', 'string', 'max:255'],
            'seo_url_canonical' => ['nullable', 'string', 'max:255'],
        ];
    }
}
