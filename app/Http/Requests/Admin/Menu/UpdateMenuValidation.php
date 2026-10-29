<?php

namespace App\Http\Requests\Admin\Menu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateMenuValidation extends FormRequest
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
            'title'              => ['required','max:150','string','unique:menus,title,'.$this->id],
            'slug'               => ['required','max:150','string','unique:menus,slug,'.$this->id],
            'sub_title'          => ['nullable','max:150','string'],
            'menu_url'           => ['required','max:255', 'url'],
            'rank'               => ['required','numeric'],
            'parent_id'          => ['nullable','numeric'],
            'status'             => ['nullable','boolean']
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->title),
            'status' => $this->status ? 1 : 0,
        ]);
    }
}
