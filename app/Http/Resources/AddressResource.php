<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Address */
class AddressResource extends JsonResource
{
    /**
     * @param Address $address
     */
    public function __construct(private readonly Address $address)
    {
        parent::__construct($address);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->address->id,
            'country' => $this->address->country,
            'subject' => $this->address->subject,
            'city' => $this->address->city,
            'street' => $this->address->street,
            'house' => $this->address->house,
            'flat' => $this->address->flat,
            'created_at' => $this->address->created_at,
            'updated_at' => $this->address->updated_at,
        ];
    }
}
