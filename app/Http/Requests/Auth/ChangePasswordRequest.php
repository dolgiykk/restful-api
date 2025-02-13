<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class ChangePasswordRequest extends Request
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'password' => 'string|required|min:8',
            'new_password' => 'string|required|confirmed|min:8',
        ];
    }
}
