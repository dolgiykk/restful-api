<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class ShowResetPasswordFormRequest extends Request
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
        ];
    }
}
