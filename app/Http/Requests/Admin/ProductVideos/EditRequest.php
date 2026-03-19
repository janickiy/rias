<?php

namespace App\Http\Requests\Admin\ProductVideos;

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
            'id' => ['required', 'integer', 'exists:product_videos,id'],
            'video' => ['required', 'string', 'max:255'],
        ];
    }
}
