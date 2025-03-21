<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'country',
        'subject',
        'city',
        'street',
        'house',
        'flat',
    ];

    /**
     * @return BelongsToMany<User, covariant Address>
     */
    public function users(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'user_addresses')
            ->using(UserAddress::class)
            ->withTimestamps()
            ->withPivot('deleted_at');
    }
}
