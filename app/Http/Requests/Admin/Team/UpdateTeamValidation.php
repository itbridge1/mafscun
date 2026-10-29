<?php

namespace App\Http\Requests\Admin\Team;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateTeamValidation extends FormRequest
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
            'name'          => ['required', 'max:255'],
            'designation'   => ['nullable', 'max:255',],
            'organization'  => ['nullable', 'max:255',],
            'phone'         => ['nullable', 'max:255',],
            'email'         => ['nullable','email', 'max:255',],
            'main_image'    => ['file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp','nullable'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'required'],
        ];
    }

}
