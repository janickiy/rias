<?php

namespace App\Http\Requests\Admin\Gaz;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:40',
            'weight' => 'nullable|numeric',
            'chemical_formula' => 'required|max:20',
            'chemical_formula_html' => 'required|max:60',
            'gaz_group_id' => 'required|array',
        ];
    }
}
