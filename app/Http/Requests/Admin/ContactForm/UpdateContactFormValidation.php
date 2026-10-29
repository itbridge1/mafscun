<?php

namespace App\Http\Requests\Admin\ContactForm;

use App\Models\Notice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UpdateContactFormValidation extends FormRequest
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
            'name' => ['required', 'max:155', ],
            'email' => ['required', 'max:155', 'unique:contact_forms,email,'.$this->id],
            'subject' => ['nullable', 'max:155',],
            'phone' => ['required', 'max:15'],
            'message' => ['nullable'],
            'status' => ['boolean', 'nullable'],
        ];
    }
    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status  ? 1 : 0,
        ]);
    }

}




