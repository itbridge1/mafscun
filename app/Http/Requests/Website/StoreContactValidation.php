<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactValidation extends FormRequest
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
            'name'                => ['required','max:150','string'],
            'email'               => ['required','email','max:150'],
            'subject'             => ['required','string','max:100'],
            'phone'               => ['required','string','max:15'],
            'message'             => ['required','string','max:255'],
        ];
    }
}
