<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class LoginRequest extends Request
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'login' => 'string|required',
            'password' => 'string|required|min:8',
        ];
    }
}
