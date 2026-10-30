<?php

namespace App\Http\Requests\Admin\ResourceSubType;

use App\Facades\ViewHelper;
use App\Models\ResourceSubType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreResourceSubTypeValidation extends FormRequest
{
    public function rules()
    {
        return [
            'resource_type_id' => ['required', 'integer', 'exists:resource_types,id'],
            'title'            => ['required', 'max:155'],
            'slug'             => ['required', 'max:155', 'unique:resource_sub_types,slug'],
            'description'      => ['nullable'],
            'status'           => ['boolean', 'nullable'],
            'rank'             => ['required', 'numeric', 'gt:0'],
        ];
    }

    public function prepareForValidation()
    {
        $slug = Str::slug($this->title);
        if (!$slug) {
            $slug = 'resource-sub-type-' . time();
        }
        if (ResourceSubType::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $this->merge([
            'slug'   => $slug,
            'rank'   => ViewHelper::getMaxRank(ResourceSubType::class),
            'status' => $this->status ? 1 : 0,
        ]);
    }
}
