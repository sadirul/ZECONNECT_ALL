<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;

class AuthResendOtpRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'digits:10'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $mobile = preg_replace('/\D+/', '', (string) $this->input('mobile'));

        $this->merge([
            'mobile' => $mobile,
        ]);
    }
}
