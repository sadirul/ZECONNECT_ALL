<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;

class AuthLoginRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'digits:10'],
            'password' => ['required', 'string'],
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
