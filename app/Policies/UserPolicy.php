<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the requested user.
     *
     * @param  \App\User  $requestUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $requestUser, User $user)
    {
        return $requestUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the requested user.
     *
     * @param  \App\User  $requestUser
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $requestUser, User $user)
    {
        return $requestUser->id === $user->id;
    }
}
