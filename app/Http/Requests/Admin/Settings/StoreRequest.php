<?php

namespace App\Http\Requests\Admin\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key_cd' => ['required', 'string', 'max:255', 'unique:settings,key_cd'],
            'type' => ['required', 'string', 'max:255'],
            'display_value' => ['nullable', 'string', 'max:255'],
            'value' => ['nullable'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $type = strtoupper((string) $this->input('type'));

            if ($type === 'FILE' && !$this->hasFile('value')) {
                $validator->errors()->add('value', 'Поле value обязательно для типа FILE.');
            }

            if ($type !== 'FILE' && blank($this->input('value'))) {
                $validator->errors()->add('value', 'Поле value обязательно.');
            }
        });
    }
}
