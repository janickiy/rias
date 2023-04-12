<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'gaz_id' => 'required|integer',
            'convertType' => 'required',
            'value' => 'required|numeric',
        ];
    }
}
