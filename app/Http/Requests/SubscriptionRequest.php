<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    public function authorize()
    {
        return backpack_auth()->check();
    }

    public function rules()
    {
        return [
            'company_id' => 'required|exists:companies,id',
            'plan_id'    => 'required|exists:plans,id',
            'is_active'  => 'nullable|boolean',
        ];
    }
}
