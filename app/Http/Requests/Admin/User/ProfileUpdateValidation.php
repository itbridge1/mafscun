<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;

class ProfileUpdateValidation extends FormRequest
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
            'name'              => ['required','max:20','string'],
            'email'             => ['required','email','unique:users,email,'.auth()->user()->id],
            'address'           => ['nullable','max:255'],
            'profile_photo'     => ['sometimes','max:2048','file'],
            'main_signature'    => ['sometimes','max:2048','file'],
        ];
    }

}
