<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class AuthUpdateProfileRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        /** @var \App\Models\User|null $user */
        $user = $this->user('api');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'address' => ['required', 'string', 'max:1000'],
            'profile_pic' => ['nullable', 'image', 'max:4096'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $email = strtolower(trim((string) $this->input('email')));

        $this->merge([
            'email' => $email,
        ]);
    }
}
