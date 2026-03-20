<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;

class AddCustomMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idmenu' => ['required', 'integer', 'exists:menus,id'],
            'labelmenu' => ['required', 'string', 'max:255'],
            'linkmenu' => ['required', 'string', 'max:1000'],
            'rolemenu' => ['nullable', 'integer'],
        ];
    }
}
