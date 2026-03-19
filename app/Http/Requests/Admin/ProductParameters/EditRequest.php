<?php

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
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }
}
