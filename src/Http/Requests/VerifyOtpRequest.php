<?php

namespace Laratribe\NativephpAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'code' => ['required', 'string'],
            'type' => ['required', 'string', 'in:register,reset-password'],
        ];
    }
}
