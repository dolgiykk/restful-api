<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class UpdateAddressRequest extends Request
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'user_id' => 'integer',
            'country' => 'string',
            'subject' => 'string|nullable',
            'city' => 'string|nullable',
            'street' => 'string',
            'house' => 'string',
            'flat' => 'string|nullable',
        ];
    }
}
