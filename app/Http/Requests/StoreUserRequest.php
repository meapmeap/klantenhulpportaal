<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->rol === 'admin';
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'voornaam' => 'required|string|max:40',
            'achternaam' => 'required|string|max:60',
            'email_adres' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email_adres')->ignore($this->route('user')),
            ],
            'wachtwoord' => $user ? 'nullable|string|min:8' : 'required|string|min:8',
            'telefoonnummer' => 'nullable|string|max:20',
            'rol' => 'required|string|in:user,admin',
        ];
    }
}
