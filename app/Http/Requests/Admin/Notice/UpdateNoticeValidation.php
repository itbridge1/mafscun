<?php

namespace App\Http\Requests\Admin\Notice;

use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateNoticeValidation extends FormRequest
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
	 * 'end_date'      => ['required','date','date_format:Y-m-d','after:' . date('Y-m-d'),],
     */
    public function rules()
    {
        return [
            'title'         => ['required', 'max:155', 'unique:notices,title,'.$this->id],
            'slug'          => ['required', 'max:155', 'unique:notices,slug,'.$this->id],
            'sub_title'     => ['nullable', 'max:155',],
            'start_date'    => ['nullable','date','date_format:Y-m-d'],
            'end_date'      => ['nullable','date','date_format:Y-m-d'],
            'main_image'    => ['file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp','nullable'],
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
