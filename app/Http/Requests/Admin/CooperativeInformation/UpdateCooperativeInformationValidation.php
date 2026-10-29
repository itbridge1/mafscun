<?php

namespace App\Http\Requests\Admin\CooperativeInformation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCooperativeInformationValidation extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('cooperativeInformation');
        return [
            'cooperative_name' => ['required', 'string', 'max:255'],
            'member_no'        => ['required', 'string', 'max:255', 'unique:cooperative_informations,member_no,' . $id],
            'address'          => ['required', 'string', 'max:255'],
            'status'           => ['required', 'boolean'],
        ];
    }
}
