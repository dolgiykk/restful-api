<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * @param User $user
     * @return void
     */
    public function created(User $user): void
    {
        logger()->channel('user')->info(__('crud.created_success'), ['user_id' => $user->id]);
    }

    /**
     * @param User $user
     * @return void
     */
    public function updated(User $user): void
    {
        logger()->channel('user')->info(__('crud.updated_success'), ['user_id' => $user->id]);
    }

    /**
     * @param User $user
     * @return void
     */
    public function deleted(User $user): void
    {
        logger()->channel('user')->info(__('crud.deleted_success'), ['user_id' => $user->id]);
    }
}
