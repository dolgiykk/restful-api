<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;

class StoreAddressRequest extends Request
{
    use ValidationTrait;

    /**
     * @return string[]
     */
    public function rules(): array
    {
        return [
            'country' => 'string|required',
            'subject' => 'string|nullable',
            'city' => 'string',
            'street' => 'string|required',
            'house' => 'string|required',
            'flat' => 'integer|nullable',
        ];
    }
}
