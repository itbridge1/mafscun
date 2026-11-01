<?php

namespace App\Http\Requests\Admin\Resource;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreResourceValidation extends FormRequest
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
            'resource_type_id'      => ['numeric','nullable'],
            'resource_sub_type_id'  => ['numeric','nullable','exists:resource_sub_types,id'],
            'title'                 => ['required', 'max:155', 'unique:resources'],
            'slug'                  => ['required', 'max:155', 'unique:resources'],
            'type'              => ['required', 'in:link,file'],
            'sub_title'         => ['nullable','max:191', 'string'],
            'status'            => ['boolean', 'nullable'],
            'main_file'         => ['required_if:type,file','required_if:type,popup','file', 'max:16360',],
            'link'              => ['nullable','url'],
            'description'       => ['nullable',],
        ];

    }

    public function prepareForValidation()
    {
        $slug = Str::slug($this->title);
        if (!$slug) {
            $slug = 'resource-' . time();
        }
        if (\App\Models\Resource::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $this->merge([
            'resource_sub_type_id' => $this->resource_sub_type_id ? (int)$this->resource_sub_type_id : null,
            'slug'                 => $slug,
            'status'               => $this->status ? 1 : 0
        ]);
    }

}
