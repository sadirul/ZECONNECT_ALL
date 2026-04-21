<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;

class AuthResetPasswordRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'digits:10'],
            'otp' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
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
