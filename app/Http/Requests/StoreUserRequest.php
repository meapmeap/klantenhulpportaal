<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->rol === 'administrator';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 
            'voornaam' => 'required|string|max:40', 
            'achternaam' => 'required|string|max:60', 
            'email_adres' => 'required|email|max:255|unique:users,email_adres', 
            'wachtwoord' => 'required|string|min:8', 
            'telefoonnummer' => 'nullable|string|max:20', 
            'rol' => 'required|string|in:user,administrator',
        ];
    }
}
