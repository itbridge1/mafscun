<?php

namespace App\Http\Requests\Admin\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionValidation extends FormRequest
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
            'name'              => ['required','max:20','string','unique:permissions,name,'.$this->id],
            'guard_name'        => ['nullable'],
            'display_name'      => ['required','max:60','string'],
            'group'             => ['required','max:60','string'],
            'group_head'        => ['boolean','nullable'],
            'description'       => ['nullable'],
        ];
    }
}
