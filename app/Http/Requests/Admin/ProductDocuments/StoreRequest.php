<?php

namespace App\Http\Requests\Admin\ProductDocuments;

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
            'file' => 'required|mimes:doc,pdf,docx,txt,pdf,xls,xlsx,odt,ods',
            'description' => 'required',
            'product_id' => 'required|integer|exists:products,id'
        ];
    }
}
