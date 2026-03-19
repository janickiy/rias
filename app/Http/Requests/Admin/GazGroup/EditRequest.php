<?php

namespace App\Http\Requests\Admin\GazGroup;

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
            'id' => ['required', 'integer', 'exists:gaz_group,id'],
            'name' => ['required', 'string', 'max:255'],
            'name_ru' => ['required', 'string', 'max:255'],
        ];
    }
}
