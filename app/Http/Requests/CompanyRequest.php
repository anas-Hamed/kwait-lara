<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
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
            'ar_name' => 'required|unique:companies,ar_name|max:100',
            'en_name' => 'required|unique:companies,en_name|max:100',
            'about' => 'nullable|min:15|max:1000',
            'category_id' => ['required',Rule::exists('categories','id')->whereNotNull('parent_id')],
            'email' => "email|nullable|unique:companies,email",
            'phone' => 'required|numeric|digits_between:4,16',
            'whatsapp' => 'nullable|numeric|digits_between:4,16',
            'website' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'facebook' => 'nullable|url',
            'snapchat' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'location' => "nullable",
            'phones' => "nullable|array",
            'image' => 'nullable|image',
            'images' => 'nullable|array',
            'images.*' => 'image|dimensions:ratio=10/4',
            'work_times' => 'required|array',
            'work_times.*.day' => 'required|in:1,2,3,4,5,6,7',
            'work_times.*.start_time' => 'required',
            'work_times.*.end_time' => 'required',
            'work_times.*.active' => 'required|boolean',
            'tags' => ['nullable','array'],
            'tags.*' => ['string']

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }

    protected function prepareForValidation()
    {
        if($this['phones'] && gettype($this['phones'][0]) == 'string'){
            $this['phones'] = collect($this->phones)->map(function ($el){
                return (array) json_decode($el);
            })->toArray();
        }
        if($this['work_times'] && gettype($this['work_times'][0]) == 'string'){
            $this['work_times'] = collect($this->work_times)->map(function ($el){
                return (array) json_decode($el);
            })->toArray();
        }
        if(gettype($this['location']) == 'string'){
            $this['location'] = (array) json_decode($this['location']);
        }



    }
}
