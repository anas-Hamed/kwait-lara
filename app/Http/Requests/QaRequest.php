<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QaRequest extends FormRequest
{
    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
        ];
    }
}
