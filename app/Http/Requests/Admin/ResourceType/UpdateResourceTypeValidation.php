<?php

namespace App\Http\Requests\Admin\ResourceType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateResourceTypeValidation extends FormRequest
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
            'title'         => ['required', 'max:155', 'unique:about_us,title,'.$this->id],
            'slug'          => ['required', 'max:155', 'unique:about_us,slug,'.$this->id],
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
