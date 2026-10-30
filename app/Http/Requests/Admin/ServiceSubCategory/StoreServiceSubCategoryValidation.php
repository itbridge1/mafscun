<?php

namespace App\Http\Requests\Admin\ServiceSubCategory;

use App\Facades\ViewHelper;
use App\Models\ServiceSubCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreServiceSubCategoryValidation extends FormRequest
{
    public function rules()
    {
        return [
            'service_category_id' => ['required', 'integer', 'exists:service_categories,id'],
            'title'               => ['required', 'max:155'],
            'slug'                => ['required', 'max:155', 'unique:service_sub_categories,slug'],
            'sub_title'           => ['nullable', 'max:155'],
            'main_image'          => ['nullable', 'file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'description'         => ['nullable'],
            'status'              => ['boolean', 'nullable'],
            'rank'                => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $slug = Str::slug($this->title);
        if (!$slug) {
            $slug = 'sub-service-' . time();
        }
        if (ServiceSubCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $this->merge([
            'slug'   => $slug,
            'rank'   => ViewHelper::getMaxRank(ServiceSubCategory::class),
            'status' => $this->status ? 1 : 0,
        ]);
    }
}
