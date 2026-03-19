<?php

namespace App\Http\Requests\Admin\Gaz;

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
            'id' => ['required', 'integer', 'exists:gaz,id'],
            'title' => ['required', 'string', 'max:40'],
            'weight' => ['nullable', 'numeric'],
            'chemical_formula' => ['required', 'string', 'max:20'],
            'chemical_formula_html' => ['required', 'string', 'max:60'],
            'gaz_group_id' => ['nullable', 'array'],
            'gaz_group_id.*' => ['integer', 'exists:gaz_group,id'],
        ];
    }
}
