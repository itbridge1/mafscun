<?php

namespace App\Http\Requests\Admin\ServiceCategory;

use App\Facades\ViewHelper;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreServiceCategoryValidation extends FormRequest
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
            'title'         => ['required', 'max:155', 'unique:service_categories'],
            'slug'          => ['required', 'max:155', 'unique:service_categories'],
            'sub_title'     => ['nullable', 'max:155',],
            'main_image'    => ['nullable', 'file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'parent_id'     => ['nullable','numeric'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
            'rank'          => ['required', 'numeric','gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'rank' => ViewHelper::getMaxRank(ServiceCategory::class)
        ]);
    }

}
