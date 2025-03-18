<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends Request
{
    use ValidationTrait;

    /**
     * @return array|mixed[]
     */
    public function rules(): array
    {
        return [
            'login' => ['string', Rule::unique('users')->ignore($this->route('id'))],
            'email' => ['string', Rule::unique('users')->ignore($this->route('id'))],
            'birthday' => 'date',
            'sex' => 'string|in:male,female',
        ];
    }
}
