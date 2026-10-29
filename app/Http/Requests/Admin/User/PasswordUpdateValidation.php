<?php

namespace App\Http\Requests\Admin\User;

use App\Models\FiscalYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordUpdateValidation extends FormRequest
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
        $this->customValidation();
        return [
            'old_password'          => ['required','password_match'],
            'password'              => ['required','confirmed','min:6'],
            'password_confirmation'   => ['required'],
        ];
    }

    public function customValidation()
    {
        Validator::extend('password_match',function ($attribute, $value, $parameters, $validator) {

            if (Hash::check($value, Auth::user()->password))

                return true;

            return false;

        });
    }

    public function messages()
    {
        return [
            'old_password.password_match'  => "Your old password doesn't match.",
        ];
    }
}
