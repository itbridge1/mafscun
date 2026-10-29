<?php

namespace App\Http\Requests\Admin\SiteSetting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingValidation extends FormRequest
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
        $rules = [
            'title'                     => ['required', 'max:155', ],
            'primary_color'             => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color'           => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sub_title'                 => ['nullable', 'max:155',],
            'main_logo'                 => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,webp'],
            'main_favicon'              => ['nullable', 'file', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,ico,webp'],
            'main_company_logo'         => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,webp'],
            'main_powered_by_logo'      => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,webp'],
            'main_stat_map'             => ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,webp'],
            'company_alias'             => ['nullable', 'string', 'max:20'],
            'pan_no'                    => ['nullable', 'string', 'max:255'],
            'reg_no'                    => ['nullable', 'string', 'max:255'],
            'email'                     => ['nullable', 'email', 'max:255'],
            'phone'                     => ['nullable', 'string', 'max:255'],
            'location'                  => ['nullable', 'string', 'max:255'],
            'statistics_text'           => ['nullable', 'string', 'max:255'],
            'footer_text'               => ['nullable'],
            'footer_1'                  => ['nullable', 'integer', 'exists:menus,id'],
            'footer_2'                  => ['nullable', 'integer', 'exists:menus,id'],
            'footer_3'                  => ['nullable', 'integer', 'exists:menus,id'],
            'footer_4'                  => ['nullable', 'integer', 'exists:menus,id'],
            'about_1'                   => ['nullable', 'integer', 'exists:about_us,id'],
            'about_2'                   => ['nullable', 'integer', 'exists:about_us,id'],
            'about_3'                   => ['nullable', 'integer', 'exists:about_us,id'],
            'about_4'                   => ['nullable', 'integer', 'exists:about_us,id'],
            'map'                       => ['nullable', 'string'],
            'social'                    => ['nullable', 'array'],
            'social.facebook_link'      => ['nullable','string','max:2048'],
            'social.twitter_link'       => ['nullable','string','max:2048'],
            'social.youtube_link'       => ['nullable','string','max:2048'],
            'social.insta_link'         => ['nullable','string','max:2048'],
            'copyright_text'            => ['nullable', 'string', 'max:255'],
            'powered_by'                => ['nullable', 'string', 'max:255'],
            'powered_by_link'           => ['nullable', 'string', 'max:2048'],
            'keywords'                  => ['nullable', 'string'],
            'description'               => ['nullable', 'string'],
            'statisticsData'            => ['nullable', 'array'],
            'statisticsData.*.id'       => ['nullable', 'integer', 'exists:statistics_details,id'],
            'statisticsData.*.name'     => ['required_with:statisticsData.*.value', 'string', 'max:255'],
            'statisticsData.*.value'    => ['required_with:statisticsData.*.name', 'string', 'max:255'],
            'statisticsData.*.rank'     => ['required_with:statisticsData.*.name', 'integer', 'min:0'],
        ];

        foreach (array_keys($this->allFiles()) as $fileKey) {
            if (preg_match('/^stat_photo_\d+$/', $fileKey)) {
                $rules[$fileKey] = ['nullable', 'image', 'max:2048', 'mimes:jpg,png,jpeg,gif,tif,bmp,webp'];
            }
        }

        return $rules;
    }
}
