<?php

namespace App\Http\Requests\Admin\ServiceSubCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateServiceSubCategoryValidation extends FormRequest
{
    public function rules()
    {
        $recordId = $this->route('serviceSubCategory') ?? $this->id;
        if (is_object($recordId)) {
            $recordId = $recordId->id;
        }

        return [
            'service_category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'title'               => ['required', 'max:155'],
            'slug'                => ['required', 'max:155', 'unique:service_sub_categories,slug,' . $recordId],
            'sub_title'           => ['nullable', 'max:155'],
            'main_image'          => ['nullable', 'file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'description'         => ['nullable'],
            'status'              => ['boolean', 'nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug'   => Str::slug($this->title),
            'status' => $this->status ? 1 : 0,
        ]);
    }
}
