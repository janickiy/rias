<?php

namespace App\Http\Requests\Admin\ProductDocuments;

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
            'id' => ['required', 'integer', 'exists:product_documents,id'],
            'description' => ['required', 'string'],
            'file' => ['nullable', 'file', 'mimes:doc,pdf,docx,txt,xls,xlsx,odt,ods'],
        ];
    }
}
