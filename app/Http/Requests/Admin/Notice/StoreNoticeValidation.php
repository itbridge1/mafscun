<?php

namespace App\Http\Requests\Admin\Notice;

use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreNoticeValidation extends FormRequest
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
            'title'         => ['required', 'max:155', 'unique:notices'],
            'slug'          => ['required', 'max:155', 'unique:notices'],
            'sub_title'     => ['nullable', 'max:155',],
            'start_date'    => ['nullable','date','date_format:Y-m-d'],
            'end_date'      => ['nullable','date','date_format:Y-m-d','after:' . date('Y-m-d'),],
            'main_image'    => ['file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp','required'],
            'description'   => ['nullable'],
            'status'        => ['boolean', 'nullable'],
            'is_popup'      => ['boolean', 'nullable'],
            'is_career'     => ['boolean', 'nullable'],
        ];
    }

    public function prepareForValidation()
    {
        $count = Notice::count();
        $this->merge([
            'slug' => Str::slug($this->title).'_'.$count,
            'status' => $this->status  ? 1 : 0,
            'is_popup' => $this->is_popup  ? 1 : 0,
            'is_career' => $this->is_career  ? 1 : 0
        ]);
    }

}
