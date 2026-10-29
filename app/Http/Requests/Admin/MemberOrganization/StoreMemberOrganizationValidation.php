<?php

namespace App\Http\Requests\Admin\MemberOrganization;

use App\Models\MemberOrganization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StoreMemberOrganizationValidation extends FormRequest
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
            'name'                  =>['required','max:155','unique:member_organizations'],
            'slug'                  =>['required','max:155','unique:member_organizations'],
            'municipality_id'       =>['numeric','required'],
            'district_id'           =>['required','numeric'],
            'bod_chief'             =>['nullable','max:255'],
            'manager'               =>['nullable','max:255'],
            'contact_no'            =>['nullable','max:255',],
            'phone'                 =>['nullable','max:255'],
            'email'                 =>['nullable','email', 'max:255'],
            'website'               =>['nullable', 'url'],
            'total_shareholder'     =>['nullable','numeric'],
            'join_year'             =>['nullable', 'numeric'],
            'status'                =>['nullable', 'boolean'],
            'rank'                  =>['nullable','numeric'],
            'association'           =>['nullable', 'boolean'],
            'pan_no'                =>['nullable', 'max:255'],
            'reg_no'                =>['nullable', 'max:255'],
            'main_image'            =>['nullable', 'file', 'max:2048'],
            'description'           =>['nullable']
        ];

    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug'          => Str::slug($this->name),
            'rank'          => MemberOrganization::max('rank') + 1,
        ]);
    }

}
