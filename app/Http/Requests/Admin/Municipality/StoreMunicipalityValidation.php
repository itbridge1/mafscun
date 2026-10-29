<?php

namespace App\Http\Requests\Admin\Municipality;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreMunicipalityValidation extends FormRequest
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
            'name'                      => ['required', 'max:155','unique:municipalities'],
            'nepali_name'               => ['required', 'max:155'],
            'province_id'               => ['required', 'numeric'],
            'district_id'               => ['required', 'numeric'],
            'link'                      => ['nullable', 'max:65355'],
            'main_image'                => ['required','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'status'                    => ['boolean', 'required'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ? 1 : 0
        ]);
    }
}
