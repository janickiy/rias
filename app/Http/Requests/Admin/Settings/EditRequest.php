<?php

namespace App\Http\Requests\Admin\Settings;

use App\Models\Settings;
use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $setting = Settings::find($this->id);
        $valueRule = $setting && strtoupper($setting->getRawOriginal('type')) === 'FILE'
            ? ['nullable', 'file']
            : ['required', 'string'];

        return [
            'id' => ['required', 'integer', 'exists:settings,id'],
            'key_cd' => ['required', 'string', 'max:255', 'unique:settings,key_cd,' . $this->id],
            'display_value' => ['nullable', 'string', 'max:255'],
            'value' => $valueRule,
        ];
    }
}
