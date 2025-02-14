<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param User $user
     */
    public function __construct(private readonly User $user)
    {
        parent::__construct($user);
    }

    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user->id,
            'login' => $this->user->login,
            'email' => $this->user->email,
            'email_verified_at' => $this->user->email_verified_at,
            'created_at' => $this->user->created_at,
            'updated_at' => $this->user->updated_at,
            'first_name' => $this->user->first_name,
            'last_name' =>$this->user->last_name,
            'second_name' =>$this->user->second_name,
            'birthday'=>$this->user->birthday,
            'sex'=>$this->user->sex,
        ];
    }
}
