<?php

namespace App\Http\Requests\Website;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuggestionValidation extends FormRequest
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
            'name'                  => ['required', 'max:150', 'string'],
            'organization'          => ['required', 'max:255', 'string'],
            'address'               => ['required', 'max:255', 'string'],
            'management_feedback'   => ['required', 'in:very_good,good,satisfactory,needs_improvement'],
            'notification'          => ['required', 'in:yes,no'],
            'report_feedback'       => ['required', 'string'],
            'policy_feedback'       => ['required', 'string'],
            'other_suggestions'     => ['required', 'string'],
        ];
    }
    
    
}
