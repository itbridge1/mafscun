<?php

namespace App\Http\Requests\Admin\Slider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateSliderValidation extends FormRequest
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
            'alt_text'         => ['required', 'max:155'],
            'caption'          => ['required', 'max:155'],
            'main_image'       => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'status'           => ['boolean', 'required'],
        ];
    }
}
