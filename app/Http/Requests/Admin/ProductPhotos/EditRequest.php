<?php

namespace App\Http\Requests\Admin\ProductPhotos;

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
            'id' => ['required', 'integer', 'exists:product_photos,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'alt' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
        ];
    }
}
