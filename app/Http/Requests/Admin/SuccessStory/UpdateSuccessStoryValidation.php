<?php

namespace App\Http\Requests\Admin\SuccessStory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateSuccessStoryValidation extends FormRequest
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
            'name'                      => ['required', 'max:155',],
            'membership_no'             => ['nullable', 'max:155'],
            'organization'              => ['nullable', 'max:155'],
            'designation'               => ['nullable', 'max:155'],
            'phone'                     => ['nullable', 'max:15'],
            'email'                     => ['nullable', 'max:155'],
            'description'               => ['required', ],
            'main_image'                => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
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
