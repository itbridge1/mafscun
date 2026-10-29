<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class StoreUserValidation extends FormRequest
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
            'name'              => ['required','max:20','string'],
            'email'             => ['required','email','unique:users'],
            'password'          => ['required','confirmed','min:8'],
            'main_image'        => ['nullable','file','max:2048','mimes:jpg,jpeg,png,bmp,tiff'],
            'main_signature'    => ['nullable','file','max:2048','mimes:jpg,jpeg,png,bmp,tiff'],
            'address'           => ['nullable','max:255'],
            'role'              => ['required','max:255','role_id_validation'],
        ];
    }

    public function customValidation()
    {
        Validator::extend('role_id_validation',function ($attribute, $value, $parameters, $validator) {

            if (Role::find($value))

                return true;

            return false;

        });
    }

    public function messages()
    {
        return [
           'role.role_id_validation' => 'Please select valid role'
        ];
    }
}
