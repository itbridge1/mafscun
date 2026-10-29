<?php

namespace App\Http\Requests\Admin\District;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateDistrictValidation extends FormRequest
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
            'name'                      => ['required', 'max:155','unique:districts,name,'.$this->id],
            'nepali_name'               => ['required', 'max:155'],
            'province_id'               => ['required', 'numeric'],
            'headquarter'               => ['nullable', 'string'],
            'area'                      => ['nullable', 'numeric'],
            'main_image'                => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'autoselect'                => ['nullable', 'boolean'],
            'status'                    => ['boolean', 'required'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ? 1 : 0,
            'autoselect' => $this->autoselect ? 1 : 0
        ]);
    }
}
