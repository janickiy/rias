<?php

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
            'alt' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif', 'max:2048'],
        ];
    }
}
