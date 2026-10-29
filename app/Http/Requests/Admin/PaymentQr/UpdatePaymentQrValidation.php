<?php

namespace App\Http\Requests\Admin\PaymentQr;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePaymentQrValidation extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'     => ['required', 'max:255'],
            'details'   => ['nullable'],
            'main_qr'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
            'status'    => ['nullable', 'boolean'],
        ];
    }
}
