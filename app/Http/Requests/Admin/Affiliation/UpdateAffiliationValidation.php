<?php

namespace App\Http\Requests\Admin\Affiliation;

use App\Models\Affiliation;
use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateAffiliationValidation extends FormRequest
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
        $recordId = $this->route('affiliation') ?? $this->id;
        if (is_object($recordId)) {
            $recordId = $recordId->id;
        }

        return [
            'title'         => ['required', 'max:155', 'unique:affiliations,title,'.$recordId],
            'slug'          => ['required', 'max:155', 'unique:affiliations,slug,'.$recordId],
            'year'          => ['required' ],
            'link'          => ['nullable' ],
            'rank'          => ['nullable','numeric','gt:0' ],
            'main_image'    => ['nullable','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'status'        => ['boolean', 'nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $count = Affiliation::count();
        $this->merge([
            'slug' => Str::slug($this->title).'_'.$count,
            'status' => $this->status  ? 1 : 0,

        ]);
    }
}
