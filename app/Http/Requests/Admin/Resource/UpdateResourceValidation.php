<?php

namespace App\Http\Requests\Admin\Resource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateResourceValidation extends FormRequest
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
            'title'                 => ['required', 'max:155', 'unique:resources,title,'.$this->id],
            'slug'                  => ['required', 'max:155', 'unique:resources,slug,'.$this->id],
            'resource_type_id'      => ['numeric', 'nullable'],
            'type'                  => ['required', 'in:link,file'],
            'sub_title'             => ['nullable','max:191', 'string'],
            'status'                => ['boolean', 'nullable'],
            'main_file'             => ['required_if:type,file','file', 'max:16360',],
            'link'                  => ['nullable','url'],
            'description'           => ['nullable',],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'status' => $this->status ? 1 : 0
        ]);
    }
}
