<?php

namespace App\Http\Requests\Admin\AboutUs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateAboutUsValidation extends FormRequest
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
        $recordId = $this->route('messageCommitment') ?? $this->route('aboutU') ?? $this->route('aboutUs') ?? $this->id;
        if (is_object($recordId)) {
            $recordId = $recordId->id;
        }

        return [
            'title'         => ['required', 'max:155', 'unique:about_us,title,'.$recordId],
            'slug'          => ['required', 'max:155', 'unique:about_us,slug,'.$recordId],
            'sub_title'     => ['nullable', 'max:155',],
            'main_image'    => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
            'is_message'    => ['boolean', 'nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'is_message' => $this->has('is_message') ? ($this->boolean('is_message') ? 1 : 0) : 0,
        ]);
    }
}
