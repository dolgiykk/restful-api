<?php

namespace App\Http\Requests\Address;

use App\Http\Requests\Request;
use App\Traits\ValidationTrait;
use Illuminate\Validation\Rule;

class UserAddressAttachRequest extends Request
{
    use ValidationTrait;

    /**
     * @return array<mixed>
     */
    public function rules(): array
    {
        return [
            'address_id' => [
                'required',
                'exists:addresses,id',
                Rule::unique('user_addresses')->where(function ($query) {
                    return $query->where('user_id', $this->route('user'));
                }),
            ],
        ];
    }
}
