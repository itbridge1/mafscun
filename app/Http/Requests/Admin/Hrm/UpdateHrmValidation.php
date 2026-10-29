<?php

namespace App\Http\Requests\Admin\Hrm;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateHrmValidation extends FormRequest
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
            'title'         => ['required', 'max:155', 'unique:hrms,title,'.$this->id],
            'slug'          => ['required', 'max:155', 'unique:hrms,slug,'.$this->id],
            'parent_id'     => ['nullable', 'numeric',],
            'main_image'    => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title)
        ]);
    }
}
