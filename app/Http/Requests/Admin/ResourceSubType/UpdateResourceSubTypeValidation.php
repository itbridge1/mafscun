<?php

namespace App\Http\Requests\Admin\ResourceSubType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateResourceSubTypeValidation extends FormRequest
{
    public function rules()
    {
        $recordId = $this->route('resourceSubType') ?? $this->id;
        if (is_object($recordId)) {
            $recordId = $recordId->id;
        }

        return [
            'resource_type_id' => ['required', 'integer', 'exists:resource_types,id'],
            'title'            => ['required', 'max:155'],
            'slug'             => ['required', 'max:155', 'unique:resource_sub_types,slug,' . $recordId],
            'description'      => ['nullable'],
            'status'           => ['boolean', 'nullable'],
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
