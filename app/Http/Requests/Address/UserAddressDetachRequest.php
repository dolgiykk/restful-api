<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class UserAddressDetachRequest extends Request
{
    use ValidationTrait;

    /**
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            'address_ids' => 'required|array',
            'address_ids.*' => 'exists:addresses,id',
        ];
    }
}
