<?php

namespace App\Http\Requests\Admin\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleValidation extends FormRequest
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
            'name'              => ['required','max:20','string','unique:roles'],
            'guard_name'        => ['nullable'],
            'display_name'      => ['required','max:60','string'],
            'type'              => ['required','max:60','string'],
            'description'       => ['nullable'],
        ];
    }
}
