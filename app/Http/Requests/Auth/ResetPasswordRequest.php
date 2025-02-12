<?php

namespace App\Http\Requests\Auth;

use App\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed',
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
