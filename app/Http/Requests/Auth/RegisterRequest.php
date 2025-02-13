<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class RegisterRequest extends Request
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
}
