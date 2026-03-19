<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\ImageItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('sanctum')->check() ;
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
            'image' => 'nullable|image',
            'images' => ['nullable','array'],
            'images_to_delete' => 'nullable|array',
            'images.*' => 'image',
            'work_times' => 'required|array',
            'work_times.*.day' => 'required|in:1,2,3,4,5,6,7',
            'work_times.*.start_time' => 'required',
            'work_times.*.end_time' => 'required',
            'work_times.*.active' => 'required|boolean',
            'tags' => ['nullable','array'],
            'tags.*' => ['string']
        ];
    }

    protected function prepareForValidation()
    {
        if($this['work_times'] && gettype($this['work_times'][0]) == 'string'){
            $this['work_times'] = collect($this->work_times)->map(function ($el){
                return (array) json_decode($el);
            })->toArray();
        }
        if(gettype($this['location']) == 'string'){
            $this['location'] = (array) json_decode($this['location']);
        }
        if(is_null($this['location'])){
            $this['location'] = null;
        }
    }
}
