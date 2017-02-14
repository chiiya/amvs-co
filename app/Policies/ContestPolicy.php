<?php

namespace App\Policies;

use App\User;
use App\Contest;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create contests.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the contest.
     *
     * @param  App\User  $user
     * @param  App\Contest  $contest
     * @return mixed
     */
    public function update(User $user, Contest $contest)
    {
        return $contest->admins()->contains('user_id', $user->id);
    }

    /**
     * Determine whether the user can delete the contest.
     *
     * @param  App\User  $user
     * @param  App\Contest  $contest
     * @return mixed
     */
    public function delete(User $user, Contest $contest)
    {
        return $user->id === $contest->creator_id;
    }
}
