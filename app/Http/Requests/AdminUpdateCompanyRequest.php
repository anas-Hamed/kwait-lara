<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ImageItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminUpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  auth('backpack')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'ar_name' => ['required',Rule::unique('companies','ar_name')->ignore($this->id)],
            'en_name' => ['required',Rule::unique('companies','en_name')->ignore($this->id)],
            'about' => 'nullable|min:15',
            'category_id' => ['required',Rule::exists('categories','id')->whereNotNull('parent_id')],
            'email' => ["email","nullable",Rule::unique('companies','email')->ignore($this->id)],
            'phone' => 'required|numeric|digits_between:4,16',
            'whatsapp' => 'nullable|numeric|digits_between:4,16',
            'website' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'facebook' => 'nullable|url',
            'snapchat' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'location' => "nullable",
            'image' => 'nullable',
            'images' => ['nullable','array'],
            'images_to_delete' => 'nullable|array',
            'images.*' => 'image',
            'tags' => ['nullable','array'],
            'tags.*' => ['string']
        ];
    }
}
