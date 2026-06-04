<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['required', Rule::exists('companies', 'id')],
            'ar_title' => 'required|max:150',
            'en_title' => 'required|max:150',
            'ar_description' => 'nullable|max:1000',
            'en_description' => 'nullable|max:1000',
            'image' => 'nullable|image',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'nullable|numeric|min:0|lt:old_price',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ];
    }
}
