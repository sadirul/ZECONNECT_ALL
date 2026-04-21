<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;
use Illuminate\Support\Str;

class AuthRegisterRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'digits:10'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $mobile = preg_replace('/\D+/', '', (string) $this->input('mobile'));
        $email = Str::lower(trim((string) $this->input('email')));

        $this->merge([
            'mobile' => $mobile,
            'email' => $email,
        ]);
    }
}
