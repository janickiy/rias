<?php

namespace App\Http\Requests\Admin\ProductVideos;

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
            'video' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }
}
