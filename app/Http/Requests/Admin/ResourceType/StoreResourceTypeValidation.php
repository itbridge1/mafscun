<?php

namespace App\Http\Requests\Admin\ResourceType;

use App\Facades\ViewHelper;
use App\Models\ResourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreResourceTypeValidation extends FormRequest
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
            'title'         => ['required', 'max:155', 'unique:about_us'],
            'slug'          => ['required', 'max:155', 'unique:about_us'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
            'rank'          => ['required', 'numeric','gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'rank' => ViewHelper::getMaxRank(ResourceType::class)
        ]);
    }

}
