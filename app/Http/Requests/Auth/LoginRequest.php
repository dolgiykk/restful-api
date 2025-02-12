<?php

namespace App\Http\Requests\Auth;

use App\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
