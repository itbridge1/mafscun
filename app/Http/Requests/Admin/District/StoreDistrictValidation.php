<?php

namespace App\Http\Requests\Admin\District;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreDistrictValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'                      => ['required', 'max:155','unique:districts'],
            'nepali_name'               => ['required', 'max:155'],
            'province_id'               => ['required', 'numeric'],
            'headquarter'               => ['nullable', 'string', 'max:155'],
            'area'                      => ['nullable', 'numeric'],
            'main_image'                => ['required','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
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
