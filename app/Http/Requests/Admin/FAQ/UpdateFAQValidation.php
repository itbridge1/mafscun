<?php

namespace App\Http\Requests\Admin\FAQ;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateFAQValidation extends FormRequest
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
        'question'         => ['required'],
        'answer'          => ['required'],
        'link'             => ['nullable'],
        'status'        => ['boolean', 'nullable'],

    ];
}
    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status  ? 1 : 0,
        ]);
    }


}
