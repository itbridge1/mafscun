<?php

namespace App\Http\Requests\Admin\Gallery;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateGalleryValidation extends FormRequest
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
            'title'                     => ['required','max:100','string','unique:galleries,title,'.$this->id],
            'slug'                      => ['required','max:150','string','unique:galleries,slug,'.$this->id],
            'sub_title'                 => ['nullable', 'max:255','string'],
            'main_photo'                => ['nullable','file','max:2048','mimes:jpg,jpeg,png,bmp,tiff,gif'],
            'status'                    => ['nullable','boolean'],
            'description'               => ['nullable', 'max:65535'],
            'gallery.*.alt_text'        => ['nullable', 'max:255','string'],
            'gallery.*.caption'         => ['nullable', 'max:255','string'],
            'gallery.*.status'          => ['nullable', 'boolean'],
            'gallery.*.gallery_image'   => ['nullable','file','max:2048','mimes:jpg,jpeg,png,bmp,tiff,gif'],

        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'slug'      => Str::slug($this->title)
        ]);
    }
}
