<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreAgmValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'                => ['required', 'max:150', 'string'],
            'nepali_name'         => ['nullable', 'max:150', 'string'],
            'gender'              => ['required', 'in:male,female,other'],
            'saccos_union'        => ['required', 'max:255', 'string'],
            'cooperative_name'    => ['required', 'max:255', 'string'],
            'post'                => ['required', 'string'],
            'email'               => ['required', 'email', 'max:255'],
            'district_id'         => ['required'],
            'municipality_id'     => ['required', 'string'],
            'ward_no'             => ['required', 'integer', 'min:1'],
            'membership_no'       => ['required', 'max:150', 'string'],
            'mobile_no'           => ['required', 'max:15', 'string'],
            'main_nomination_form'     => ['required', 'file','max:5120'],
            'main_photo'               => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
        ];
    
    }
}
