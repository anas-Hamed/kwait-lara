<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOfferRequest extends FormRequest
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
            'ar_title' => 'sometimes|required|max:150',
            'en_title' => 'sometimes|required|max:150',
            'ar_description' => 'nullable|max:1000',
            'en_description' => 'nullable|max:1000',
            'image' => 'nullable|image',
            'old_price' => 'nullable|numeric|min:0',
            'new_price' => 'nullable|numeric|min:0|lt:old_price',
            'starts_at' => 'sometimes|required|date',
            'ends_at' => 'sometimes|required|date|after:starts_at',
            'is_active' => 'nullable|boolean',
        ];
    }
}
