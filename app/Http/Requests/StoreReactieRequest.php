<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReactieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bericht' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'bericht.required' => 'Een reactie is verplicht.',
            'bericht.string' => 'De reactie moet tekst bevatten.',
        ];
    }
}