<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;

class AuthVerifyOtpRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'digits:10'],
            'otp' => ['required', 'digits:6'],
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
