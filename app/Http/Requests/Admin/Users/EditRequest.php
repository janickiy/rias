<?php

namespace App\Http\Requests\Admin\Users;

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
            'id' => ['required', 'integer', 'exists:users,id'],
            'login' => ['required', 'string', 'max:255', 'unique:users,login,' . $this->id],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
            'password_again' => ['nullable', 'string', 'min:6', 'same:password'],
        ];
    }
}
