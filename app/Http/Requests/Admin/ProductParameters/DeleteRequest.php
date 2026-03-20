<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\ProductParameters;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'exists:product_parameters,id'],
        ];
    }
}
