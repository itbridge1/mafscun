<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateNewsValidation extends FormRequest
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
            'category_id'   =>['nullable', 'numeric'],
            'title'         => ['required', 'max:155'],
            'slug'          => ['required', 'max:155', 'unique:news,slug,'.$this->id],
            'sub_title'     => ['required', 'max:155', ],
            'main_image'    => ['file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp','required'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
            'view_count'    => ['numeric', 'nullable'],
            'author'        => ['required', 'max:155',],
            'location'      => ['nullable', ],
        ];
    }

    public function prepareForValidation()
{
    $this->merge([
        'slug' => Str::slug($this->title) . '-' . time(),
    ]);
}
}
