<?php

namespace App\Http\Requests\Admin\ProductParameters;

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
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ];
    }
}
