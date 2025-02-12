<?php

namespace App\Http\Requests\Auth;

use App\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'login' => 'string|required|unique:users',
            'email' => 'string|required|email|unique:users',
            'password' => 'string|required|min:8',
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
