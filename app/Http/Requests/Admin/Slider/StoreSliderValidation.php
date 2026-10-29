<?php

namespace App\Http\Requests\Admin\Slider;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreSliderValidation extends FormRequest
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
            'alt_text'         => ['required', 'max:155'],
            'caption'          => ['required', 'max:155'],
            'main_image'       => ['required','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'status'           => ['boolean', 'required'],
            'rank'             => ['required', 'numeric','gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'rank'      => Team::max('rank') + 1
        ]);
    }
}
