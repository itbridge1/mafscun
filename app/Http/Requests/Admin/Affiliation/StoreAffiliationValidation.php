<?php

namespace App\Http\Requests\Admin\Affiliation;

use App\Models\Affiliation;
use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreAffiliationValidation extends FormRequest
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
            'title'           => ['required', 'max:155', 'unique:affiliations'],
            'slug'            => ['required', 'max:155', 'unique:affiliations'],
            'year'            => ['required'],
            'link'            => ['nullable','url'],
            'rank'            => ['nullable','numeric','gt:0' ],
            'main_image'      => ['required','file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp'],
            'status'          => ['boolean', 'nullable'],
              ];
    }

    public function prepareForValidation()
    {
        $count = Affiliation::count();
        $this->merge([
            'slug' => Str::slug($this->title).'_'.$count,
            'rank' => Affiliation::max('rank') + 1,
            ]);
    }

}
